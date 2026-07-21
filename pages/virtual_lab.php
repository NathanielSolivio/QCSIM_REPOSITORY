<!-- 
 Credits: 
 Creators: Nathaniel P. Solivio, Jae Marianne Almazan and Hayya Michaela Cajuday
 Programmed/Written by: Nathaniel P. Solivio
 Tested by: Nathaniel P. Solivio, Jae Marianne Almazan and Hayya Michaela Cajuday
 Design by: Nathaniel P. Solivio, Jae Marianne Almazan and Hayya Michaela Cajuday
-->
<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Virtual Lab — QCSim</title>
  <link rel="stylesheet" href="/qcsim/Assets/MainWebsite/style.css">
  <style>
    /* ── RESET & BASE ─────────────────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Nunito', sans-serif;
      background: #1a1a2e;
      overflow: hidden;
      height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ── NAVBAR OVERRIDE (keep slim) ──────────────────────────────────────── */
    .navbar { flex-shrink: 0; }

    /* ── LAB WRAPPER ──────────────────────────────────────────────────────── */
    .lab-wrapper {
      flex: 1;
      position: relative;
      overflow: hidden;
    }

    /* ── BACKGROUND ───────────────────────────────────────────────────────── */
    .lab-bg {
      position: absolute;
      inset: 0;
      background: url('/qcsim/Assets/VirtualLab/lab_bg.png') center bottom / cover no-repeat;
      z-index: 0;
    }

    /* ── TOP-LEFT CONTROLS ────────────────────────────────────────────────── */
    .lab-controls {
      position: absolute;
      top: 16px;
      left: 16px;
      z-index: 50;
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .ctrl-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 5px;
      background: rgba(255,255,255,0.92);
      border: 2px solid var(--border, #d1e4f5);
      border-radius: 12px;
      padding: 10px 14px;
      cursor: pointer;
      transition: all .2s;
      box-shadow: 0 2px 12px rgba(26,107,181,.15);
      min-width: 64px;
    }
    .ctrl-btn:hover {
      background: #fff;
      border-color: var(--primary, #1a6bb5);
      box-shadow: 0 4px 18px rgba(26,107,181,.25);
      transform: translateY(-2px);
    }
    .ctrl-btn img {
      width: 28px;
      height: 28px;
      object-fit: contain;
    }
    .ctrl-btn .ctrl-label {
      font-size: 11px;
      font-weight: 700;
      color: var(--text, #0d2d4e);
      letter-spacing: .3px;
      text-transform: uppercase;
    }
    .ctrl-btn.active-mute {
      background: #fff5f5;
      border-color: #e53e3e;
    }
    .ctrl-btn.active-mute .ctrl-label { color: #e53e3e; }

    /* ── STEP INDICATOR ───────────────────────────────────────────────────── */
    .step-indicator {
      position: absolute;
      top: 16px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 50;
      background: rgba(255,255,255,0.92);
      border: 2px solid var(--border, #d1e4f5);
      border-radius: 99px;
      padding: 7px 20px;
      font-size: 13px;
      font-weight: 700;
      color: var(--primary, #1a6bb5);
      box-shadow: 0 2px 12px rgba(26,107,181,.12);
      white-space: nowrap;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .step-dot {
      width: 8px; height: 8px; border-radius: 50%;
      background: var(--primary, #1a6bb5);
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0%,100% { opacity:1; transform:scale(1); }
      50%      { opacity:.5; transform:scale(1.4); }
    }

    /* ── RIGHT TOOLBAR ────────────────────────────────────────────────────── */
    .lab-toolbar {
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      z-index: 50;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .tool-card {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 7px;
      background: rgba(255,255,255,0.92);
      border: 2px solid var(--border, #d1e4f5);
      border-radius: 14px;
      padding: 14px 12px;
      cursor: pointer;
      transition: all .2s;
      box-shadow: 0 2px 14px rgba(26,107,181,.13);
      width: 90px;
      position: relative;
      text-decoration: none;
    }
    .tool-card:hover:not(.locked) {
      background: #fff;
      border-color: var(--primary, #1a6bb5);
      transform: translateX(-4px);
      box-shadow: 0 6px 24px rgba(26,107,181,.22);
    }
    .tool-card.locked {
      cursor: not-allowed;
      opacity: .65;
      filter: grayscale(.4);
    }
    .tool-card.active-tool {
      border-color: var(--primary, #1a6bb5);
      background: var(--primary-light, #e8f3fc);
    }
    .tool-card img {
      width: 52px;
      height: 52px;
      object-fit: contain;
    }
    .tool-name {
      font-size: 11px;
      font-weight: 700;
      color: var(--text, #0d2d4e);
      text-align: center;
      line-height: 1.3;
      letter-spacing: .2px;
    }
    .lock-badge {
      position: absolute;
      top: 6px;
      right: 6px;
      background: #718096;
      border-radius: 50%;
      width: 20px; height: 20px;
      display: flex; align-items: center; justify-content: center;
    }
    .lock-badge svg { width: 11px; height: 11px; color: #fff; }

    .tool-divider {
      width: 60px;
      height: 1.5px;
      background: var(--border, #d1e4f5);
      border-radius: 2px;
      margin: 0 auto;
    }

    /* ── NOTICE BOARD ─────────────────────────────────────────────────────── */
    .notice-board {
      position: absolute;
      right: 124px;
      top: 16px;
      z-index: 40;
      background: #ff8c00;
      color: #fff;
      font-weight: 900;
      font-size: 13px;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      padding: 6px 14px;
      border-radius: 4px;
      box-shadow: 0 2px 8px rgba(0,0,0,.2);
      writing-mode: horizontal-tb;
    }

    /* ── MODAL OVERLAY ────────────────────────────────────────────────────── */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(13,45,78,.55);
      z-index: 200;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(3px);
    }
    .modal-overlay.open { display: flex; }

    .lab-modal {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(13,45,78,.25);
      width: 90%;
      max-width: 560px;
      max-height: 92vh;
      overflow-y: auto;
      animation: modalIn .3s cubic-bezier(.34,1.56,.64,1);
    }
    @keyframes modalIn {
      from { opacity:0; transform:scale(.88) translateY(20px); }
      to   { opacity:1; transform:scale(1) translateY(0); }
    }

    .lab-modal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 22px 28px 16px;
      border-bottom: 1.5px solid var(--border, #d1e4f5);
    }
    .lab-modal-header h2 {
      font-family: 'Raleway', sans-serif;
      font-weight: 800;
      font-size: 20px;
      color: var(--text, #0d2d4e);
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .modal-icon {
      width: 40px; height: 40px;
      background: var(--primary-light, #e8f3fc);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      box-shadow: 0 2px 6px rgba(26,107,181,.12);
    }
    .modal-icon img { width: 22px; height: 22px; object-fit: contain; }
    /* Tool-specific icon backgrounds */
    .modal-icon.tool-icon-dissolution {
      background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    }
    .modal-icon.tool-icon-uvvis {
      background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    }

    .modal-close-btn {
      background: none; border: none; cursor: pointer;
      width: 32px; height: 32px; border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      color: #718096; transition: .2s;
    }
    .modal-close-btn:hover { background: #f7fafc; color: var(--text, #0d2d4e); }

    .lab-modal-body {
      padding: 24px 28px 28px;
    }

    /* Tool placeholder inside modal */
    .tool-placeholder {
      text-align: center;
      padding: 40px 20px;
      background: var(--primary-light, #e8f3fc);
      border-radius: 14px;
      border: 2px dashed var(--border, #d1e4f5);
    }
    .tool-placeholder img {
      width: 100px; height: 100px;
      object-fit: contain;
      opacity: .7;
      margin-bottom: 16px;
    }
    .tool-placeholder h3 {
      font-family: 'Raleway', sans-serif;
      font-weight: 800; font-size: 18px;
      color: var(--primary, #1a6bb5);
      margin-bottom: 8px;
    }
    .tool-placeholder p {
      color: var(--text-muted, #607d99);
      font-size: 14px;
      line-height: 1.6;
    }

    /* Locked modal */
    .locked-modal-body {
      text-align: center;
      padding: 40px 28px;
    }
    .locked-modal-body .lock-icon {
      width: 64px; height: 64px;
      background: #edf2f7;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 20px;
    }
    .locked-modal-body h3 {
      font-family: 'Raleway', sans-serif;
      font-weight: 800; font-size: 20px;
      color: var(--text, #0d2d4e);
      margin-bottom: 10px;
    }
    .locked-modal-body p {
      color: var(--text-muted, #607d99);
      font-size: 14px; line-height: 1.6;
    }

    /* ── COMPLETION BADGE ─────────────────────────────────────────────────── */
    .completion-badge {
      display: none;
      position: absolute;
      bottom: 24px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 50;
      background: linear-gradient(135deg, #38a169, #276749);
      color: #fff;
      border-radius: 99px;
      padding: 10px 24px;
      font-weight: 700;
      font-size: 14px;
      box-shadow: 0 4px 20px rgba(56,161,105,.4);
      display: flex;
      align-items: center;
      gap: 8px;
      opacity: 0;
      transition: opacity .4s;
      pointer-events: none;
    }
    .completion-badge.show { opacity: 1; pointer-events: auto; }

    /* ── TOOLTIP ──────────────────────────────────────────────────────────── */
    .tool-card[data-tooltip]:hover::after {
      content: attr(data-tooltip);
      position: absolute;
      right: calc(100% + 10px);
      top: 50%;
      transform: translateY(-50%);
      background: rgba(13,45,78,.9);
      color: #fff;
      font-size: 12px;
      font-weight: 600;
      padding: 6px 12px;
      border-radius: 8px;
      white-space: nowrap;
      pointer-events: none;
    }

    /* ── RESPONSIVE ───────────────────────────────────────────────────────── */
    @media (max-width: 600px) {
      .lab-toolbar { right: 8px; }
      .tool-card { width: 72px; padding: 10px 8px; }
      .tool-card img { width: 40px; height: 40px; }
      .lab-controls { top: 10px; left: 10px; gap: 8px; }
      .ctrl-btn { padding: 8px 10px; min-width: 54px; }
    }

    /* ── UV-VIS STEP PROGRESS ─────────────────────────────────────────────── */
    .uvvis-steps {
      display: flex;
      gap: 0;
      margin-bottom: 16px;
      border-radius: 10px;
      overflow: hidden;
      border: 1.5px solid var(--border, #d1e4f5);
    }
    .uvvis-step {
      flex: 1;
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 16px;
      font-size: 13px;
      font-weight: 700;
      color: var(--text-muted, #607d99);
      background: #f9fbfe;
      border-right: 1.5px solid var(--border, #d1e4f5);
    }
    .uvvis-step:last-child { border-right: none; }
    .uvvis-step.active {
      background: var(--primary-light, #e8f3fc);
      color: var(--primary, #1a6bb5);
    }
    .uvvis-step.done {
      background: #f0fff4;
      color: #276749;
    }
    .uvvis-step-num {
      width: 22px; height: 22px;
      border-radius: 50%;
      background: var(--border, #d1e4f5);
      color: var(--text-muted, #607d99);
      display: flex; align-items: center; justify-content: center;
      font-size: 12px; font-weight: 800; flex-shrink: 0;
    }
    .uvvis-step.active .uvvis-step-num {
      background: var(--primary, #1a6bb5);
      color: #fff;
    }
    .uvvis-step.done .uvvis-step-num {
      background: #38a169;
      color: #fff;
    }

    /* ── INSTRUCTION BANNER ───────────────────────────────────────────────── */
    .instruction-banner {
      display: flex;
      align-items: center;
      gap: 10px;
      background: var(--primary-light, #e8f3fc);
      border: 1.5px solid var(--border, #d1e4f5);
      border-radius: 10px;
      padding: 11px 16px;
      font-size: 14px;
      color: var(--text, #0d2d4e);
      margin-bottom: 18px;
    }
    .instruction-banner svg { flex-shrink: 0; color: var(--primary, #1a6bb5); }

    /* ── SPECTRO STAGE ────────────────────────────────────────────────────── */
    .spectro-stage {
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 220px;
      margin-bottom: 16px;
    }

    .spectro-machine {
      position: relative;
      display: block;
      width: 100%;
      max-width: 600px;
      margin: 0 auto;
      aspect-ratio: 1200 / 500;
      user-select: none;
    }
    .spectro-img {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: contain;
      transition: opacity .25s;
    }
    .spectro-img.hidden { display: none; }

    /* ── POWER HOTSPOT ────────────────────────────────────────────────────── */
    /* PNG: 1200×500 */
    .power-hotspot, .hotspot-power {
      position: absolute;
      left: 38.5%;
      top:  78.6%;
      transform: translate(-50%, -50%);
      width: 50px;
      height: 50px;
      cursor: pointer;
      z-index: 10;
      border-radius: 50%;
    }
    .power-hotspot.done, .hotspot-power.done { pointer-events: none; }

    /* Pulsing rings for the new hotspot-power */
    .hotspot-pulse {
      position: absolute;
      inset: 0;
      border-radius: 50%;
      border: 3px solid rgba(26,107,181,.7);
      animation: ringPulse 1.6s ease-out infinite;
      pointer-events: none;
    }
    .hotspot-pulse.delay { animation-delay: .8s; }
    .hotspot-power.done .hotspot-pulse { display: none; }

    /* ══ STEP 3: COMPARTMENT LID HOTSPOTS ════════════════════════════════ */
    .hotspot-lid {
      position: absolute !important;
      width: 11% !important;
      height: 22% !important;
      transform: translate(-50%, -50%) !important;
      border-radius: 6px;
    }
    .hotspot-lid .hotspot-pulse {
      border-radius: 6px;
      border-color: rgba(217, 119, 6, .85);
    }
    .hotspot-lid-open {
      left: 42.25% !important;
      top:  57.6% !important;
    }
    .hotspot-lid-close {
      left: 42.25% !important;
      top:  41.4% !important;
    }
    .hotspot-lid.locked {
      pointer-events: none;
    }
    .hotspot-lid.locked .hotspot-pulse { display: none; }
    .hotspot-lid.done {
      pointer-events: none;
    }
    .hotspot-lid.done .hotspot-pulse { display: none; }
    .hotspot-lid:hover {
      background: rgba(217, 119, 6, .15);
    }

    /* ══ STEP 3: BLANK CUVETTE (draggable) ═══════════════════════════════ */
    .cuvette-source {
      position: absolute;
      right: -2%;
      top: 35%;
      transform: translateY(-50%);
      z-index: 30;
      animation: trayBob 2s ease-in-out infinite;
    }
    @keyframes trayBob {
      0%, 100% { transform: translateY(-50%); }
      50%       { transform: translateY(-58%); }
    }
    .cuvette-source.hidden { display: none; }

    .cuvette-tray {
      background: #fff;
      border: 2px dashed var(--primary, #1a6bb5);
      border-radius: 12px;
      padding: 10px 14px 12px;
      box-shadow: 0 6px 18px rgba(26,107,181,.18);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
    }
    .cuvette-tray::before {
      content: "Drag me";
      font-size: 10px;
      font-weight: 700;
      color: var(--primary, #1a6bb5);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 2px;
    }

    .cuvette {
      width: 24px;
      height: 50px;
      position: relative;
      cursor: grab;
      transition: transform .15s;
    }
    .cuvette:active {
      cursor: grabbing;
      transform: scale(1.05);
    }
    .cuvette.dragging {
      opacity: .55;
    }
    .cuvette-cap {
      width: 22px;
      height: 6px;
      background: #cbd5e0;
      border: 1.5px solid #1a202c;
      border-radius: 3px 3px 0 0;
      margin: 0 auto;
      position: relative;
      z-index: 2;
    }
    .cuvette-body {
      width: 24px;
      height: 44px;
      background: #f7fafc;
      border: 1.5px solid #1a202c;
      border-radius: 0 0 4px 4px;
      position: absolute;
      top: 6px;
      left: 0;
      overflow: hidden;
    }
    .cuvette-liquid {
      position: absolute;
      bottom: 8px;
      left: 1.5px;
      right: 1.5px;
      height: 28px;
      background: linear-gradient(180deg, rgba(173, 216, 230, .55) 0%, rgba(135, 206, 235, .85) 100%);
      border-radius: 0 0 3px 3px;
    }
    .cuvette-label {
      font-size: 8px;
      font-weight: 800;
      color: var(--primary, #1a6bb5);
      letter-spacing: .5px;
      margin-top: 4px;
      text-align: center;
    }

    /* Sample cuvette variant (different color liquid) */
    .cuvette[data-type="sample"] .cuvette-liquid {
      background: linear-gradient(180deg, rgba(216, 168, 222, .55) 0%, rgba(192, 132, 204, .85) 100%);
    }
    .cuvette[data-type="sample"] .cuvette-label {
      color: #8b5cf6;
    }

    /* ══ STEP 3: DROP ZONE ═══════════════════════════════════════════════ */
    .cuvette-drop-zone {
      position: absolute;
      left: 42.25%;
      top:  57.6%;
      width: 11%;
      height: 22%;
      transform: translate(-50%, -50%);
      border: 2.5px dashed var(--primary, #1a6bb5);
      border-radius: 6px;
      background: rgba(26,107,181, .1);
      z-index: 25;
      display: flex;
      align-items: center;
      justify-content: center;
      pointer-events: auto;
      animation: dropZonePulse 1.6s ease-in-out infinite;
    }
    .cuvette-drop-zone.hidden { display: none; }
    .cuvette-drop-zone.over {
      background: rgba(34,197,94, .25);
      border-color: #16a34a;
      transform: translate(-50%, -50%) scale(1.05);
    }
    @keyframes dropZonePulse {
      0%, 100% { box-shadow: 0 0 0 0 rgba(26,107,181, .4); }
      50%      { box-shadow: 0 0 0 8px rgba(26,107,181, 0); }
    }
    .drop-zone-hint {
      font-size: 10px;
      font-weight: 800;
      color: var(--primary, #1a6bb5);
      text-transform: uppercase;
      letter-spacing: 1px;
      background: rgba(255,255,255,.85);
      padding: 2px 8px;
      border-radius: 4px;
    }
    .cuvette-drop-zone.over .drop-zone-hint {
      color: #16a34a;
    }

    /* ══ STEP 4: SET ZERO HOTSPOT ════════════════════════════════════════ */
    /* PNG: 1200×500 */
    .hotspot-setzero {
      position: absolute !important;
      left: 51.5% !important;
      top:  67.2% !important;
      width: 9% !important;
      height: 10% !important;
      transform: translate(-50%, -50%) !important;
      border-radius: 99px;
    }
    .hotspot-setzero .hotspot-pulse {
      border-radius: 99px;
      border-color: rgba(34, 197, 94, .85);
    }
    .hotspot-setzero.locked {
      pointer-events: none;
    }
    .hotspot-setzero.locked .hotspot-pulse { display: none; }
    .hotspot-setzero.done {
      pointer-events: none;
    }
    .hotspot-setzero.done .hotspot-pulse { display: none; }
    .hotspot-setzero:hover {
      background: rgba(34, 197, 94, .15);
    }

    /* ══ STEP 4: REMOVE BLANK CUVETTE HOTSPOT ════════════════════════════ */
    .hotspot-remove-blank {
      position: absolute !important;
      left: 42.25% !important;
      top:  57.6% !important;
      width: 11% !important;
      height: 22% !important;
      transform: translate(-50%, -50%) !important;
      border-radius: 6px;
    }
    .hotspot-remove-blank .hotspot-pulse {
      border-radius: 6px;
      border-color: rgba(220, 38, 38, .85);
    }
    .hotspot-remove-blank.locked {
      pointer-events: none;
    }
    .hotspot-remove-blank.locked .hotspot-pulse { display: none; }
    .hotspot-remove-blank.done {
      pointer-events: none;
    }
    .hotspot-remove-blank.done .hotspot-pulse { display: none; }
    .hotspot-remove-blank:hover {
      background: rgba(220, 38, 38, .15);
    }

    /* ══ SET ZERO FLASH NOTIFICATION ═════════════════════════════════════ */
    .lab-modal.zero-flash {
      animation: zeroFlash 1.2s ease;
    }
    @keyframes zeroFlash {
      0%   { box-shadow: 0 20px 60px rgba(13,45,78,.25); }
      30%  { box-shadow: 0 0 0 8px rgba(34,197,94,.6), 0 20px 60px rgba(13,45,78,.25); }
      60%  { box-shadow: 0 0 0 4px rgba(34,197,94,.3), 0 20px 60px rgba(13,45,78,.25); }
      100% { box-shadow: 0 20px 60px rgba(13,45,78,.25); }
    }

    .zero-toast {
      position: fixed;
      top: 24px;
      left: 50%;
      transform: translateX(-50%) translateY(-80px);
      background: linear-gradient(135deg, #16a34a, #15803d);
      color: #fff;
      padding: 14px 24px;
      border-radius: 99px;
      font-weight: 700;
      font-size: 14px;
      box-shadow: 0 10px 30px rgba(22,163,74,.4);
      z-index: 999;
      display: flex;
      align-items: center;
      gap: 10px;
      opacity: 0;
      transition: all .4s cubic-bezier(.34,1.56,.64,1);
    }
    .zero-toast.show {
      transform: translateX(-50%) translateY(0);
      opacity: 1;
    }

    /* ══ STEP 5: SAMPLE CUVETTE TRAY (offset slightly so it's distinguishable) ══ */
    #sampleCuvetteSource {
      top: 35%;
    }

    /* ══ STEP 6: READ ABSORBANCE HOTSPOT ═════════════════════════════════ */
    /* PNG: 1200×500 */
    .hotspot-read {
      position: absolute !important;
      left: 59.6% !important;
      top:  66.8% !important;
      width: 9% !important;
      height: 10% !important;
      transform: translate(-50%, -50%) !important;
      border-radius: 99px;
    }
    .hotspot-read .hotspot-pulse {
      border-radius: 99px;
      border-color: rgba(168, 85, 247, .85);
    }
    .hotspot-read.locked {
      pointer-events: none;
    }
    .hotspot-read.locked .hotspot-pulse { display: none; }
    .hotspot-read.done {
      pointer-events: none;
    }
    .hotspot-read.done .hotspot-pulse { display: none; }
    .hotspot-read:hover {
      background: rgba(168, 85, 247, .15);
    }

    /* ══ ABSORBANCE READING MODAL ════════════════════════════════════════ */
    .abs-display {
      background: linear-gradient(180deg, #0a1f10 0%, #0e2c17 100%);
      border: 2.5px solid var(--border, #d1e4f5);
      border-radius: 14px;
      padding: 28px 24px;
      text-align: center;
      box-shadow: inset 0 2px 10px rgba(0,0,0,.5);
      margin: 18px 0 22px;
    }
    .abs-label {
      font-family: 'Courier New', monospace;
      font-size: 12px;
      font-weight: 700;
      color: rgba(74,222,128,.7);
      letter-spacing: 2px;
      text-transform: uppercase;
      margin-bottom: 10px;
    }
    .abs-value {
      font-family: 'Courier New', monospace;
      font-size: 56px;
      font-weight: 800;
      color: #4ade80;
      letter-spacing: 3px;
      text-shadow: 0 0 18px rgba(74,222,128,.7);
      line-height: 1;
      min-height: 56px;
    }
    .abs-value.reading {
      animation: absScan 1.4s ease-in-out infinite;
    }
    @keyframes absScan {
      0%, 100% { opacity: 1;   text-shadow: 0 0 18px rgba(74,222,128,.7); }
      50%       { opacity: .35; text-shadow: 0 0 6px  rgba(74,222,128,.3); }
    }
    .abs-unit {
      font-family: 'Courier New', monospace;
      font-size: 14px;
      color: rgba(74,222,128,.7);
      margin-top: 6px;
      letter-spacing: 2px;
    }

    /* Wavelength info row in absorbance modal */
    .abs-info {
      display: flex;
      justify-content: space-between;
      gap: 14px;
      padding: 12px 16px;
      background: var(--primary-light, #e8f3fc);
      border-radius: 10px;
      margin-bottom: 16px;
      font-size: 13px;
    }
    .abs-info-row {
      display: flex;
      flex-direction: column;
      gap: 2px;
      flex: 1;
    }
    .abs-info-label {
      color: var(--text-muted, #607d99);
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .5px;
    }
    .abs-info-value {
      color: var(--text, #0d2d4e);
      font-weight: 800;
      font-size: 15px;
    }

    /* Completion celebration */
    .completion-card {
      text-align: center;
      padding: 18px 0;
      animation: fadeUp .5s ease;
    }
    .completion-icon {
      width: 64px;
      height: 64px;
      margin: 0 auto 12px;
      border-radius: 50%;
      background: linear-gradient(135deg, #16a34a, #15803d);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 8px 24px rgba(22,163,74,.4);
      animation: pop .5s cubic-bezier(.34,1.56,.64,1);
    }
    @keyframes pop {
      0%   { transform: scale(0);   opacity: 0; }
      80%  { transform: scale(1.1);            }
      100% { transform: scale(1);   opacity: 1; }
    }
    .completion-card h3 {
      font-family: 'Raleway', sans-serif;
      font-weight: 800;
      font-size: 20px;
      color: var(--text, #0d2d4e);
      margin-bottom: 6px;
    }
    .completion-card p {
      color: var(--text-muted, #607d99);
      font-size: 14px;
    }


    /* Pulsing ring hint */
    .hotspot-ring {
      position: absolute;
      inset: -6px;
      border-radius: 50%;
      border: 2.5px solid rgba(26,107,181,.6);
      animation: ringPulse 1.6s ease-out infinite;
    }
    .hotspot-ring.delay { animation-delay: .8s; }
    @keyframes ringPulse {
      0%   { transform: scale(.7);  opacity: 1; }
      100% { transform: scale(1.6); opacity: 0; }
    }
    .power-hotspot.done .hotspot-ring { display: none; }

    /* Button face */
    .hotspot-face {
      position: absolute;
      inset: 0;
      border-radius: 50%;
      background: #e2e8f0;
      border: 2px solid #a0aec0;
      display: flex; align-items: center; justify-content: center;
      color: #4a5568;
      box-shadow: 0 2px 8px rgba(0,0,0,.15), inset 0 1px 0 rgba(255,255,255,.5);
      transition: all .15s;
    }
    .power-hotspot:hover .hotspot-face {
      background: #cbd5e0;
      transform: scale(1.1);
      box-shadow: 0 4px 14px rgba(26,107,181,.3), inset 0 1px 0 rgba(255,255,255,.5);
    }
    .power-hotspot:active .hotspot-face {
      transform: scale(.92);
      box-shadow: inset 0 2px 6px rgba(0,0,0,.2);
    }
    .power-hotspot.done .hotspot-face {
      background: #276749;
      border-color: #276749;
      color: #fff;
    }

    /* ── INDICATOR LIGHT OVERLAY ──────────────────────────────────────────── */
    .indicator-light {
      position: absolute;
      left: 87%;
      top:  81%;
      transform: translate(-50%, -50%);
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: #e53e3e;
      box-shadow: 0 0 6px rgba(229,62,62,.8);
      transition: background .4s, box-shadow .4s;
      pointer-events: none;
      z-index: 11;
    }
    .indicator-light.on {
      background: #38a169;
      box-shadow: 0 0 10px rgba(56,161,105,.9), 0 0 20px rgba(56,161,105,.4);
      animation: lightGlow 2s ease-in-out infinite;
    }
    @keyframes lightGlow {
      0%,100% { box-shadow: 0 0 8px rgba(56,161,105,.9), 0 0 18px rgba(56,161,105,.4); }
      50%      { box-shadow: 0 0 14px rgba(56,161,105,1), 0 0 28px rgba(56,161,105,.6); }
    }

    /* ── CALLOUT LABELS ───────────────────────────────────────────────────── */
    .callout-wrap {
      position: absolute;
      inset: 0;
      pointer-events: none;
    }
    .callout {
      position: absolute;
      font-size: 12px;
      font-weight: 700;
      color: var(--text, #0d2d4e);
      white-space: nowrap;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
      transition: opacity .4s;
    }
    .callout-line {
      width: 1.5px;
      height: 28px;
      background: var(--text, #0d2d4e);
      opacity: .5;
    }
    /* Power button callout — below the button */
    .callout-power {
      left: 42%;
      top:  90%;
      transform: translate(-50%, 0);
    }
    /* Indicator callout — below indicator, slightly left */
    .callout-indicator {
      left: 28%;
      top:  90%;
      transform: translate(-50%, 0);
    }
    .callout-wrap.hidden { opacity: 0; pointer-events: none; }

    /* ── SUCCESS MESSAGE ──────────────────────────────────────────────────── */
    .step-success {
      display: flex;
      align-items: center;
      gap: 10px;
      background: #f0fff4;
      border: 1.5px solid #c6f6d5;
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 14px;
      font-weight: 700;
      color: #276749;
      animation: fadeUp .4s ease;
    }
    .step-success.hidden { display: none; }
    @keyframes fadeUp {
      from { opacity:0; transform:translateY(8px); }
      to   { opacity:1; transform:translateY(0); }
    }

    /* ── CLICK RIPPLE ─────────────────────────────────────────────────────── */
    .ripple {
      position: absolute;
      border-radius: 50%;
      background: rgba(56,161,105,.35);
      pointer-events: none;
      animation: rippleOut .6s ease-out forwards;
      transform: translate(-50%, -50%);
      z-index: 20;
    }
    @keyframes rippleOut {
      from { width:0; height:0; opacity:1; }
      to   { width:80px; height:80px; opacity:0; }
    }
    /* ══ STEP 2: WAVELENGTH BUTTON ═══════════════════════════════════════ */
        .wl-open-btn {
          display: inline-flex;
          align-items: center;
          gap: 8px;
          margin: 14px auto 0;
          padding: 11px 22px;
          background: var(--primary, #1a6bb5);
          color: #fff;
          border: none;
          border-radius: 99px;
          font-family: 'Nunito', sans-serif;
          font-weight: 700;
          font-size: 14px;
          cursor: pointer;
          transition: all .2s;
          box-shadow: 0 4px 14px rgba(26,107,181,.3);
          position: relative;
          overflow: visible;
        }
        .wl-open-btn:not(.locked):hover {
          transform: translateY(-2px);
          box-shadow: 0 6px 20px rgba(26,107,181,.4);
          background: var(--primary-dark, #0d4f8a);
        }
        .wl-open-btn.locked {
          background: #cbd5e0;
          color: #718096;
          cursor: not-allowed;
          box-shadow: none;
        }
        .wl-btn-pulse {
          position: absolute;
          inset: -3px;
          border-radius: 99px;
          border: 2.5px solid var(--primary, #1a6bb5);
          animation: ringPulse 1.6s ease-out infinite;
          pointer-events: none;
        }
        .wl-open-btn.locked .wl-btn-pulse { display: none; }
        .wl-open-btn.done .wl-btn-pulse { display: none; }
        .wl-open-btn.done {
          background: #16a34a;
          pointer-events: none;
        }

    /* ══ SHOW LABELS BUTTON & OVERLAY ════════════════════════════════════ */
    .labels-toggle-btn {
      position: absolute;
      top: 8px;
      right: 8px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--surface, #fff);
      border: 1.5px solid var(--border, #d1e4f5);
      color: var(--primary, #1a6bb5);
      font-family: 'Nunito', sans-serif;
      font-weight: 700;
      font-size: 12px;
      padding: 6px 12px;
      border-radius: 99px;
      cursor: pointer;
      transition: all .2s;
      z-index: 5;
    }
    .labels-toggle-btn:hover {
      background: var(--primary-light, #e8f3fc);
      border-color: var(--primary, #1a6bb5);
    }

    .labels-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(13,45,78,.65);
      z-index: 300;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(4px);
    }
    .labels-overlay.open { display: flex; }

    .labels-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(13,45,78,.3);
      width: 90%;
      max-width: 720px;
      max-height: 88vh;
      overflow-y: auto;
      animation: modalIn .3s cubic-bezier(.34,1.56,.64,1);
    }
    .labels-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 18px 24px 14px;
      border-bottom: 1.5px solid var(--border, #d1e4f5);
    }
    .labels-card-header h3 {
      font-family: 'Raleway', sans-serif;
      font-weight: 800;
      font-size: 18px;
      color: var(--text, #0d2d4e);
    }
    .labels-img {
      display: block;
      width: 100%;
      height: auto;
      padding: 16px 24px 24px;
    }

    /* ══ WAVELENGTH MODAL ════════════════════════════════════════════════ */
    .wl-hint {
      font-size: 13px;
      color: var(--text-muted, #607d99);
      text-align: center;
      margin-bottom: 14px;
    }

    .wl-display {
      background: linear-gradient(180deg, #0a1f10 0%, #0e2c17 100%);
      border: 2.5px solid var(--border, #d1e4f5);
      border-radius: 12px;
      padding: 18px 20px;
      display: flex;
      align-items: baseline;
      justify-content: center;
      gap: 8px;
      box-shadow: inset 0 2px 8px rgba(0,0,0,.4);
      margin-bottom: 14px;
      min-height: 76px;
    }
    .wl-display.shake {
      animation: shake .4s ease;
      border-color: #e53e3e;
    }
    @keyframes shake {
      0%,100% { transform: translateX(0); }
      25%      { transform: translateX(-6px); }
      75%      { transform: translateX(6px); }
    }
    .wl-display.flash {
      animation: flash .5s ease;
    }
    @keyframes flash {
      0%   { background: #14532d; }
      100% { background: #0a1f10; }
    }
    .wl-value {
      font-family: 'Courier New', monospace;
      font-size: 38px;
      font-weight: 700;
      color: #4ade80;
      letter-spacing: 2px;
      text-shadow: 0 0 12px rgba(74,222,128,.6);
      line-height: 1;
    }
    .wl-unit {
      font-family: 'Courier New', monospace;
      font-size: 16px;
      font-weight: 700;
      color: #4ade80;
      opacity: .75;
    }

    /* Arrow controls */
    .wl-arrows {
      display: flex;
      gap: 12px;
      justify-content: center;
      margin-bottom: 14px;
    }
    .wl-arrow-btn {
      width: 56px;
      height: 40px;
      border-radius: 10px;
      border: 1.5px solid var(--border, #d1e4f5);
      background: var(--primary-light, #e8f3fc);
      color: var(--primary, #1a6bb5);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all .15s;
      user-select: none;
    }
    .wl-arrow-btn:hover {
      background: var(--primary, #1a6bb5);
      color: #fff;
      border-color: var(--primary, #1a6bb5);
    }
    .wl-arrow-btn:active {
      transform: scale(.95);
    }

    /* Numpad */
    .wl-numpad {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      margin-bottom: 14px;
    }
    .wl-key {
      padding: 14px 0;
      border-radius: 10px;
      border: 1.5px solid var(--border, #d1e4f5);
      background: var(--surface, #fff);
      color: var(--text, #0d2d4e);
      font-family: 'Nunito', sans-serif;
      font-weight: 700;
      font-size: 18px;
      cursor: pointer;
      transition: all .12s;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .wl-key:hover {
      background: var(--primary-light, #e8f3fc);
      border-color: var(--primary, #1a6bb5);
    }
    .wl-key:active {
      transform: scale(.94);
      background: var(--primary, #1a6bb5);
      color: #fff;
    }
    .wl-key-clear {
      background: #fff5f5;
      color: #e53e3e;
      border-color: #fed7d7;
    }
    .wl-key-clear:hover {
      background: #e53e3e;
      color: #fff;
      border-color: #e53e3e;
    }
    .wl-key-back {
      background: #f7fafc;
      color: #4a5568;
    }

    /* Error */
    .wl-error {
      display: flex;
      align-items: center;
      gap: 8px;
      background: #fff5f5;
      border: 1.5px solid #fed7d7;
      color: #c53030;
      padding: 10px 14px;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 12px;
      animation: fadeUp .3s ease;
    }
    .wl-error.hidden { display: none; }

    .wl-confirm {
      padding: 13px 0;
      font-size: 15px;
    }
    .wl-confirm.success {
      background: #16a34a !important;
      pointer-events: none;
    }

    /* ══ CONGRATS MODAL ══ */
    .congrats-modal {
      animation: congratsPop .5s cubic-bezier(.34, 1.56, .64, 1);
    }
    @keyframes congratsPop {
      0%   { transform: scale(.5); opacity: 0; }
      100% { transform: scale(1);  opacity: 1; }
    }
    .congrats-icon-wrap {
      width: 96px;
      height: 96px;
      border-radius: 50%;
      background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
      color: #d97706;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 22px;
      box-shadow: 0 8px 24px rgba(217, 119, 6, .25);
      animation: congratsBob 2.2s ease-in-out infinite;
    }
    .congrats-icon-wrap svg {
      width: 56px;
      height: 56px;
    }
    @keyframes congratsBob {
      0%, 100% { transform: translateY(0) rotate(-3deg); }
      50%      { transform: translateY(-6px) rotate(3deg); }
    }
    .congrats-title {
      font-family: 'Raleway', sans-serif;
      font-size: 30px;
      font-weight: 900;
      color: var(--primary, #1a6bb5);
      margin: 0 0 8px;
      letter-spacing: -.5px;
    }
    .congrats-subtitle {
      color: var(--text, #0d2d4e);
      font-size: 16px;
      font-weight: 700;
      margin: 0 0 18px;
      line-height: 1.4;
    }
    .congrats-desc {
      color: var(--text-muted, #5a6c80);
      font-size: 14px;
      line-height: 1.6;
      margin: 0 0 28px;
    }
    .congrats-actions {
      display: flex;
      gap: 10px;
      justify-content: center;
    }
    .congrats-actions .btn {
      min-width: 130px;
    }

  </style>
</head>
<body>
  <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

  <div class="lab-wrapper">

    <!-- BACKGROUND -->
    <div class="lab-bg"></div>

    <!-- TOP LEFT CONTROLS -->
    <div class="lab-controls">
      <!-- MUTE -->
      <button class="ctrl-btn" id="muteBtn" onclick="toggleMute()" title="Mute / Unmute">
        <img src="/qcsim/Assets/VirtualLab/mute.png" alt="Mute" id="muteIcon"
             onerror="this.style.display='none';document.getElementById('muteSvg').style.display='block'">
        <!-- SVG fallback -->
        <svg id="muteSvg" style="display:none;width:28px;height:28px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
          <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/>
        </svg>
        <span class="ctrl-label" id="muteLabel">Mute</span>
      </button>

      <!-- RESET -->
      <button class="ctrl-btn" id="resetBtn" onclick="resetLab()" title="Reset Lab">
        <img src="/qcsim/Assets/VirtualLab/reset.png" alt="Reset" id="resetIcon"
             onerror="this.style.display='none';document.getElementById('resetSvg').style.display='block'">
        <svg id="resetSvg" style="display:none;width:28px;height:28px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="1 4 1 10 7 10"/>
          <path d="M3.51 15a9 9 0 1 0 .49-4.5"/>
        </svg>
        <span class="ctrl-label">Reset</span>
      </button>
    </div>

    <!-- STEP INDICATOR -->
    <div class="step-indicator" id="stepIndicator">
      <div class="step-dot"></div>
      <span id="stepText">Step 1 — Select a tool from the toolbar</span>
    </div>

    <!-- RIGHT TOOLBAR -->
    <div class="lab-toolbar">

      <!-- DISSOLUTION APPARATUS -->
      <div class="tool-card"
           id="toolDissolution"
           onclick="openTool('dissolution')"
           data-tooltip="Dissolution Apparatus">
        <img src="/qcsim/Assets/VirtualLab/dissolution_apparatus.png"
             alt="Dissolution Apparatus"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <svg style="display:none;width:52px;height:52px;color:var(--primary)" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v11m0 0H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-4m-4 0v-11"/>
        </svg>
        <span class="tool-name">Dissolution<br>Apparatus</span>
      </div>

      <div class="tool-divider"></div>

      <!-- UV-VIS SPECTROPHOTOMETER -->
      <div class="tool-card locked"
           id="toolUvvis"
           onclick="openTool('uvvis')"
           data-tooltip="Complete Dissolution first">
        <div class="lock-badge">
          <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
            <rect x="3" y="11" width="18" height="11" rx="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
        </div>
        <img src="/qcsim/Assets/VirtualLab/spectro.png"
             alt="UV-VIS Spectrophotometer"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <svg style="display:none;width:52px;height:52px;color:#718096" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
        </svg>
        <span class="tool-name">UV-VIS<br>Spectro</span>
      </div>

    </div>

  </div><!-- end lab-wrapper -->

  <!-- ── DISSOLUTION APPARATUS (separate include) ──────────────────────── -->
  <?php require __DIR__ . "/../includes/dissolution.php"; ?>


  <!-- ── UV-VIS LOCKED MODAL ────────────────────────────────────────────── -->
  <div class="modal-overlay" id="modalUvvisLocked">
    <div class="lab-modal" style="max-width:420px;">
      <div class="locked-modal-body">
        <div class="lock-icon">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#718096" stroke-width="2">
            <rect x="3" y="11" width="18" height="11" rx="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
        </div>
        <h3>UV-VIS Spectrophotometer Locked</h3>
        <p>You need to complete the <strong>Dissolution Apparatus</strong> module first before accessing the UV-VIS Spectrophotometer.</p>
        <button class="btn btn-primary" style="margin-top:20px;" onclick="closeModal('modalUvvisLocked');openTool('dissolution')">
          Go to Dissolution Apparatus
        </button>
      </div>
    </div>
  </div>

  <!-- ── UV-VIS MODAL (unlocked) ────────────────────────────────────────── -->
  <div class="modal-overlay" id="modalUvvis">
    <div class="lab-modal" style="max-width:640px;">
      <div class="lab-modal-header">
        <h2>
          <div class="modal-icon tool-icon-uvvis">
            <svg width="26" height="26" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
              <!-- Light source on the left -->
              <circle cx="4" cy="16" r="2.5" fill="#fbbf24" stroke="#d97706" stroke-width="1.2"/>
              <!-- Light beams going right -->
              <path d="M6.5 16 L11 16" stroke="#fbbf24" stroke-width="2" stroke-linecap="round"/>
              <path d="M21 16 L26 16" stroke="#fbbf24" stroke-width="2" stroke-linecap="round" opacity=".55"/>
              <!-- Cuvette in the middle -->
              <rect x="11" y="8" width="10" height="18" rx="1.5" fill="#ffffff" stroke="#1a6bb5" stroke-width="1.6"/>
              <!-- Liquid inside cuvette -->
              <rect x="12.2" y="13" width="7.6" height="11.8" rx=".7" fill="#3b82f6" opacity=".55"/>
              <!-- Detector on the right -->
              <rect x="26" y="13" width="3.5" height="6" rx="1" fill="#fff" stroke="#1a6bb5" stroke-width="1.2"/>
            </svg>
          </div>
          UV-VIS Spectrophotometer
        </h2>
  <div class="modal-header-actions">
    <button class="ds-restart-btn" onclick="uvRestartTool()" title="Restart this tool from the beginning">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12a9 9 0 1 0 3-6.7"/><polyline points="3 4 3 10 9 10"/></svg>
      Restart Tool
    </button>
    <button class="modal-close-btn" onclick="closeModal('modalUvvis')">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </button>
  </div>
      </div>
      <div class="lab-modal-body" style="padding-top:16px;">

        <!-- STEP PROGRESS BAR -->
        <div class="uvvis-steps">
          <div class="uvvis-step active" id="uvStep1">
            <div class="uvvis-step-num">1</div>
            <span>Turn On</span>
          </div>
          <div class="uvvis-step" id="uvStep2">
            <div class="uvvis-step-num">2</div>
            <span>Set Wavelength</span>
          </div>
          <div class="uvvis-step" id="uvStep3">
            <div class="uvvis-step-num">3</div>
            <span>Place Blank</span>
          </div>
          <div class="uvvis-step" id="uvStep4">
            <div class="uvvis-step-num">4</div>
            <span>Set Zero</span>
          </div>
          <div class="uvvis-step" id="uvStep5">
            <div class="uvvis-step-num">5</div>
            <span>Place Sample</span>
          </div>
          <div class="uvvis-step" id="uvStep6">
            <div class="uvvis-step-num">6</div>
            <span>Read</span>
          </div>
        </div>

        <!-- INSTRUCTION BANNER -->
        <div class="instruction-banner" id="uvInstruction">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span id="uvInstructionText">Click the <strong>power button</strong> on the spectrophotometer to turn it on.</span>
        </div>

        <!-- SPECTROPHOTOMETER PNG + HOTSPOT INTERACTIVE AREA -->
        <div class="spectro-stage">
          <div class="spectro-machine" id="spectroMachine">

            <!-- OFF state image (default) -->
            <img
              src="/qcsim/Assets/VirtualLab/spectro_off.png"
              alt="Spectrophotometer (OFF)"
              class="spectro-img"
              id="spectroImgOff"
              draggable="false"
            >

            <!-- ON state image (hidden until power on) -->
            <img
              src="/qcsim/Assets/VirtualLab/spectro_on.png"
              alt="Spectrophotometer (ON)"
              class="spectro-img hidden"
              id="spectroImgOn"
              draggable="false"
            >

            <!-- LID OPEN state image (hidden until lid clicked) -->
            <img
              src="/qcsim/Assets/VirtualLab/spectro_lidOpen.png"
              alt="Spectrophotometer (lid open)"
              class="spectro-img hidden"
              id="spectroImgLidOpen"
              draggable="false"
            >

            <!-- BLANK IN state image (hidden until cuvette dropped) -->
            <img
              src="/qcsim/Assets/VirtualLab/spectro_blankIn.png"
              alt="Spectrophotometer (blank cuvette inside)"
              class="spectro-img hidden"
              id="spectroImgBlankIn"
              draggable="false"
            >

            <!-- SAMPLE IN state image (hidden until sample dropped) -->
            <img
              src="/qcsim/Assets/VirtualLab/spectro_sampleOn.png"
              alt="Spectrophotometer (sample cuvette inside)"
              class="spectro-img hidden"
              id="spectroImgSampleIn"
              draggable="false"
            >

            <!-- SAMPLE CLOSED state image — reuse spectro_on.png to show "machine ready with sample inside" -->
            <!-- We just toggle imgOn for the closed state since the closed lid covers the cuvette either way -->

            <!-- ════════ HOTSPOT: POWER BUTTON ════════
                 PNG: 1200×500 -->
            <div class="hotspot hotspot-power"
                 id="powerHotspot"
                 onclick="pressPowerButton()"
                 title="Power Button">
              <div class="hotspot-pulse"></div>
              <div class="hotspot-pulse delay"></div>
            </div>

            <!-- ════════ HOTSPOT: COMPARTMENT LID (open) ════════
                 PNG: 1200×500 -->
            <div class="hotspot hotspot-lid hotspot-lid-open locked"
                 id="lidOpenHotspot"
                 onclick="openCompartmentLid()"
                 title="Open Lid">
              <div class="hotspot-pulse"></div>
              <div class="hotspot-pulse delay"></div>
            </div>

            <!-- ════════ HOTSPOT: COMPARTMENT LID (close) ════════
                 PNG: 1200×500 -->
            <div class="hotspot hotspot-lid hotspot-lid-close locked"
                 id="lidCloseHotspot"
                 onclick="closeCompartmentLidWithBlank()"
                 title="Close Lid">
              <div class="hotspot-pulse"></div>
              <div class="hotspot-pulse delay"></div>
            </div>

            <!-- ════════ HOTSPOT: SET ZERO BUTTON ════════
                 PNG: 1200×500 -->
            <div class="hotspot hotspot-setzero locked"
                 id="setZeroHotspot"
                 onclick="pressSetZero()"
                 title="Set Zero">
              <div class="hotspot-pulse"></div>
              <div class="hotspot-pulse delay"></div>
            </div>

            <!-- ════════ HOTSPOT: REMOVE BLANK CUVETTE ════════ -->
            <div class="hotspot hotspot-remove-blank locked"
                 id="removeBlankHotspot"
                 onclick="removeBlankCuvette()"
                 title="Remove Blank">
              <div class="hotspot-pulse"></div>
              <div class="hotspot-pulse delay"></div>
            </div>

            <!-- ════════ DRAGGABLE BLANK CUVETTE ════════ -->
            <div class="cuvette-source hidden" id="cuvetteSource">
              <div class="cuvette-tray">
                <div class="cuvette" id="blankCuvette"
                     draggable="true"
                     data-type="blank"
                     ondragstart="cuvetteDragStart(event)"
                     ondragend="cuvetteDragEnd(event)">
                  <div class="cuvette-cap"></div>
                  <div class="cuvette-body"></div>
                  <div class="cuvette-liquid"></div>
                  <div class="cuvette-label">BLANK</div>
                </div>
              </div>
            </div>

            <!-- ════════ DRAGGABLE SAMPLE CUVETTE ════════ -->
            <div class="cuvette-source hidden" id="sampleCuvetteSource">
              <div class="cuvette-tray">
                <div class="cuvette" id="sampleCuvette"
                     draggable="true"
                     data-type="sample"
                     ondragstart="cuvetteDragStart(event)"
                     ondragend="cuvetteDragEnd(event)">
                  <div class="cuvette-cap"></div>
                  <div class="cuvette-body"></div>
                  <div class="cuvette-liquid"></div>
                  <div class="cuvette-label">SAMPLE</div>
                </div>
              </div>
            </div>

            <!-- DROP ZONE — same position as lid open hotspot -->
            <div class="cuvette-drop-zone hidden"
                 id="cuvetteDropZone"
                 ondragover="cuvetteDragOver(event)"
                 ondragleave="cuvetteDragLeave(event)"
                 ondrop="cuvetteDrop(event)">
              <div class="drop-zone-hint">Drop Here</div>
            </div>

            <!-- ════════ HOTSPOT: READ ABSORBANCE BUTTON ════════
                 PNG: 1200×500 -->
            <div class="hotspot hotspot-read locked"
                 id="readHotspot"
                 onclick="pressReadAbsorbance()"
                 title="Read Absorbance">
              <div class="hotspot-pulse"></div>
              <div class="hotspot-pulse delay"></div>
            </div>

          <!-- WAVELENGTH BUTTON — appears under machine after power on -->
          <button class="wl-open-btn locked"
                  id="screenHotspot"
                  style="order:99"
                  onclick="openWavelengthModal()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h2l2-7 4 14 3-9 2 5h5"/></svg>
            <span id="wlBtnLabel">Set Wavelength</span>
            <span class="wl-btn-pulse"></span>
          </button>

          <!-- SHOW LABELS BUTTON — top right of stage -->
          <button class="labels-toggle-btn" id="labelsBtn" onclick="toggleLabels()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            Show Parts
          </button>

          <!-- LABELS OVERLAY (shown on toggle) -->
          <div class="labels-overlay" id="labelsOverlay" onclick="toggleLabels()">
            <div class="labels-card" onclick="event.stopPropagation()">
              <div class="labels-card-header">
                <h3>Spectrophotometer Parts</h3>
                <button class="modal-close-btn" onclick="toggleLabels()">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
              </div>
              <img src="/qcsim/Assets/VirtualLab/spectro_labels.png" alt="Spectrophotometer parts diagram" class="labels-img">
            </div>
          </div>

        </div><!-- end spectro-stage -->

        <!-- SUCCESS MESSAGE (hidden until powered on) -->
        <div class="step-success hidden" id="stepSuccess">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11 14 15 10"/></svg>
          Spectrophotometer is ON! Proceed to the next step.
        </div>

      </div><!-- end modal body -->
    </div>
  </div>

  <!-- ══════════════════ ZERO TOAST NOTIFICATION ══════════════════ -->
  <div class="zero-toast" id="zeroToast">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11 14 15 10"/></svg>
    <span>Instrument has been set to zero</span>
  </div>

  <!-- ══════════════════ ABSORBANCE READING MODAL ══════════════════ -->
  <div class="modal-overlay" id="modalAbsorbance">
    <div class="lab-modal" style="max-width:420px;">
      <div class="lab-modal-header">
        <h2>
          <div class="modal-icon" style="background:#a855f7;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M3 12h2l2-7 4 14 3-9 2 5h5"/></svg>
          </div>
          <span id="absModalTitle">Reading Absorbance</span>
        </h2>
        <button class="modal-close-btn" id="absCloseBtn" onclick="closeAbsorbanceModal()" style="display:none;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>
      <div class="lab-modal-body" style="padding:18px 22px 22px;">

        <!-- Reading state (default) -->
        <div id="absReadingPane">
          <div class="abs-info">
            <div class="abs-info-row">
              <span class="abs-info-label">Wavelength</span>
              <span class="abs-info-value" id="absInfoWavelength">— nm</span>
            </div>
          </div>

          <div class="abs-display">
            <div class="abs-label">Absorbance (A)</div>
            <div class="abs-value reading" id="absValue">0.00</div>
            <div class="abs-unit">a.u.</div>
          </div>

          <p class="text-center text-muted" style="font-size:13px; margin-bottom:4px;" id="absStatusText">
            Reading sample…
          </p>
        </div>

        <!-- Completion state (hidden initially) -->
        <div id="absCompletionPane" class="hidden">
          <div class="completion-card">
            <div class="completion-icon">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h3>Simulation Complete!</h3>
            <p>You've successfully completed the UV-VIS Spectrophotometer protocol.</p>
          </div>
          <button class="btn btn-primary btn-full" style="margin-top:14px;" onclick="closeAbsorbanceModal()">Close</button>
        </div>

      </div>
    </div>
  </div>

  <!-- ══════════════════ CONGRATS MODAL (Lab Simulation Complete) ══════════════════ -->
  <div class="modal-overlay" id="modalCongrats" onclick="if(event.target===this)closeCongratsModal()">
    <div class="lab-modal congrats-modal" style="max-width:480px;">
      <div class="lab-modal-body" style="padding:40px 36px 32px; text-align:center;">

        <div class="congrats-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/>
            <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/>
            <path d="M4 22h16"/>
            <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/>
            <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/>
            <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>
          </svg>
        </div>

        <h2 class="congrats-title">Congratulations!</h2>
        <p class="congrats-subtitle">You've successfully completed the QC Laboratory Simulation.</p>

        <p class="congrats-desc">You went through dissolution testing and UV-VIS analysis, the same workflow used in real pharmaceutical quality control labs. Well done! 🎉</p>

        <div class="congrats-actions">
          <button class="btn btn-secondary" onclick="closeCongratsModal()">Close</button>
          <button class="btn btn-primary" onclick="runAgainFromCongrats()">Run Again</button>
        </div>

      </div>
    </div>
  </div>

  <!-- ══════════════════ WAVELENGTH INPUT MODAL ══════════════════ -->
  <div class="modal-overlay" id="modalWavelength">
    <div class="lab-modal" style="max-width:380px;">
      <div class="lab-modal-header">
        <h2>
          <div class="modal-icon" style="background:#1a6bb5;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M3 12h2l2-7 4 14 3-9 2 5h5"/></svg>
          </div>
          Set Wavelength
        </h2>
        <button class="modal-close-btn" onclick="closeWavelengthModal()">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>
      <div class="lab-modal-body" style="padding:18px 20px 22px;">

        <p class="wl-hint">Enter a wavelength between <strong>0 – 250 nm</strong>.</p>

        <!-- DISPLAY SCREEN -->
        <div class="wl-display" id="wlDisplay">
          <div class="wl-value" id="wlValue">0</div>
          <div class="wl-unit">nm</div>
        </div>

        <!-- ARROW CONTROLS -->
        <div class="wl-arrows">
          <button class="wl-arrow-btn"
                  onmousedown="startArrow(-1)" onmouseup="stopArrow()" onmouseleave="stopArrow()"
                  ontouchstart="event.preventDefault();startArrow(-1)" ontouchend="stopArrow()"
                  aria-label="Decrease">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
          </button>
          <button class="wl-arrow-btn"
                  onmousedown="startArrow(1)" onmouseup="stopArrow()" onmouseleave="stopArrow()"
                  ontouchstart="event.preventDefault();startArrow(1)" ontouchend="stopArrow()"
                  aria-label="Increase">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
          </button>
        </div>

        <!-- NUMPAD -->
        <div class="wl-numpad">
          <button class="wl-key" onclick="pressKey('1')">1</button>
          <button class="wl-key" onclick="pressKey('2')">2</button>
          <button class="wl-key" onclick="pressKey('3')">3</button>
          <button class="wl-key" onclick="pressKey('4')">4</button>
          <button class="wl-key" onclick="pressKey('5')">5</button>
          <button class="wl-key" onclick="pressKey('6')">6</button>
          <button class="wl-key" onclick="pressKey('7')">7</button>
          <button class="wl-key" onclick="pressKey('8')">8</button>
          <button class="wl-key" onclick="pressKey('9')">9</button>
          <button class="wl-key wl-key-clear" onclick="clearWavelength()">C</button>
          <button class="wl-key" onclick="pressKey('0')">0</button>
          <button class="wl-key wl-key-back" onclick="backspaceKey()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"/><line x1="18" y1="9" x2="12" y2="15"/><line x1="12" y1="9" x2="18" y2="15"/></svg>
          </button>
        </div>

        <!-- ERROR -->
        <div class="wl-error hidden" id="wlError">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span id="wlErrorText">Wavelength invalid. Please input a number between 0 – 250.</span>
        </div>

        <!-- CONFIRM -->
        <button class="btn btn-primary btn-full wl-confirm" id="wlConfirm" onclick="confirmWavelength()">
          Confirm Wavelength
        </button>

      </div>
    </div>
  </div>

  <script>
  // ── STATE ──────────────────────────────────────────────────────────────────
  let isMuted             = false;
  let dissolutionComplete = true; // set to true when dissolution module signals completion

  // ══════════════════ AUDIO MANAGER ══════════════════
  const labAudio = {
    bg:   new Audio('/qcsim/Assets/Audio/bg_music.mp3'),
    pour: new Audio('/qcsim/Assets/Audio/water_pour.mp3'),
    init() {
      this.bg.loop     = true;
      this.bg.volume   = 0.175;   // 70% of previous 0.25
      this.pour.loop   = true;
      this.pour.volume = 0.55;
      // Suppress errors if audio files are missing
      this.bg.addEventListener('error',   (e) => console.log('bg_music.mp3 error:', e));
      this.pour.addEventListener('error', (e) => console.log('water_pour.mp3 error:', e));
      this.pour.addEventListener('canplaythrough', () => console.log('water_pour.mp3 ready'));
    },
    playBg()    { if (!isMuted) this.bg.play().catch(err => console.log('bg play err:', err)); },
    pauseBg()   { this.bg.pause(); },
    startPour() {
      console.log('[Audio] startPour called, isMuted:', isMuted);
      if (isMuted) return;
      this.pour.currentTime = 0;
      this.pour.play()
        .then(() => console.log('[Audio] pour started playing'))
        .catch(err => console.log('[Audio] pour play err:', err));
    },
    stopPour() {
      console.log('[Audio] stopPour called');
      this.pour.pause();
      this.pour.currentTime = 0;
    },
    applyMute(muted) {
      this.bg.muted   = muted;
      this.pour.muted = muted;
      if (muted) this.stopPour();
    },
  };
  labAudio.init();
  window.labAudio = labAudio;     // expose globally so dissolution.php can use it

  // Start bg music after first user interaction (browsers block autoplay otherwise)
  let bgMusicStarted = false;
  document.addEventListener('click', () => {
    if (!bgMusicStarted && !isMuted) {
      labAudio.playBg();
      bgMusicStarted = true;
    }
  });

  // ── MUTE ──────────────────────────────────────────────────────────────────
  function toggleMute() {
    isMuted = !isMuted;
    const btn   = document.getElementById('muteBtn');
    const label = document.getElementById('muteLabel');
    const icon  = document.getElementById('muteIcon');
    const svg   = document.getElementById('muteSvg');

    if (isMuted) {
      btn.classList.add('active-mute');
      label.textContent = 'Unmute';
      if (icon.style.display !== 'none') icon.style.opacity = '.4';
      document.querySelectorAll('audio, video').forEach(el => el.muted = true);
    } else {
      btn.classList.remove('active-mute');
      label.textContent = 'Mute';
      if (icon.style.display !== 'none') icon.style.opacity = '1';
      document.querySelectorAll('audio, video').forEach(el => el.muted = false);
    }
    labAudio.applyMute(isMuted);
  }

  // ── RESET ──────────────────────────────────────────────────────────────────
  function resetLab() {
    if (!confirm('Reset the laboratory to the beginning?')) return;
    dissolutionComplete = false;
    lockUvvis();
    updateStep('Step 1 — Select a tool from the toolbar');
    closeAllModals();
    // Dispatch event so embedded modules can also reset
    window.dispatchEvent(new CustomEvent('labReset'));
  }

  // ── TOOL OPEN ──────────────────────────────────────────────────────────────
  function openTool(tool) {
    if (tool === 'dissolution') {
      openModal('modalDissolution');
      updateStep('Step 1 — Using Dissolution Apparatus');
    } else if (tool === 'uvvis') {
      if (!dissolutionComplete) {
        openModal('modalUvvisLocked');
      } else {
        openModal('modalUvvis');
        updateStep('Step 2 — Using UV-VIS Spectrophotometer');
      }
    }
  }

  // ── MODAL HELPERS ──────────────────────────────────────────────────────────
  function openModal(id) {
    closeAllModals();
    document.getElementById(id).classList.add('open');
  }
  function closeModal(id) {
    document.getElementById(id).classList.remove('open');
  }
  function closeAllModals() {
    document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('open'));
  }

  // Close on backdrop click
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', e => {
      if (e.target === overlay) overlay.classList.remove('open');
    });
  });

  // ── UNLOCK UV-VIS ──────────────────────────────────────────────────────────
  // Call this function from the dissolution module when it's complete:
  // window.unlockUvvis();
  function unlockUvvis() {
    dissolutionComplete = true;
    const card = document.getElementById('toolUvvis');
    card.classList.remove('locked');
    card.dataset.tooltip = 'UV-VIS Spectrophotometer';
    card.querySelector('.lock-badge').style.display = 'none';
    updateStep('Dissolution complete! Step 2 — UV-VIS Spectrophotometer is now unlocked.');
  }
  window.unlockUvvis = unlockUvvis; // expose globally

  function lockUvvis() {
    const card = document.getElementById('toolUvvis');
    card.classList.add('locked');
    card.dataset.tooltip = 'Complete Dissolution first';
    card.querySelector('.lock-badge').style.display = 'flex';
  }

  // ── STEP TEXT ──────────────────────────────────────────────────────────────
  function updateStep(text) {
    document.getElementById('stepText').textContent = text;
  }

  // ── UV-VIS STEP 1: POWER BUTTON ───────────────────────────────────────────
  let spectroOn         = false;
  let wavelengthSet     = false;
  let currentWavelength = 0;

  function pressPowerButton() {
    if (spectroOn) return;
    spectroOn = true;

    const instrText = document.getElementById('uvInstructionText');
    const success   = document.getElementById('stepSuccess');
    const hotspot   = document.getElementById('powerHotspot');
    const screenHs  = document.getElementById('screenHotspot');
    const imgOff    = document.getElementById('spectroImgOff');
    const imgOn     = document.getElementById('spectroImgOn');

    // Click ripple at hotspot
    const ripple = document.createElement('div');
    ripple.className = 'ripple';
    ripple.style.left = '38.5%';
    ripple.style.top  = '78.6%';
    document.getElementById('spectroMachine').appendChild(ripple);
    setTimeout(() => ripple.remove(), 700);

    // Mark hotspot done (stops pulse)
    hotspot.classList.add('done');

    // Crossfade OFF → ON image
    imgOff.style.transition = 'opacity .25s';
    imgOff.style.opacity = '0';
    setTimeout(() => {
      imgOff.classList.add('hidden');
      imgOff.style.opacity = '';
      imgOff.style.transition = '';
      imgOn.classList.remove('hidden');
      imgOn.style.opacity = '0';
      imgOn.style.transition = 'opacity .25s';
      requestAnimationFrame(() => { imgOn.style.opacity = '1'; });
      setTimeout(() => { imgOn.style.transition = ''; }, 300);
    }, 250);

    // Click sound
    playClickTone(600, 200);

    // Unlock the wavelength screen hotspot + advance step
    setTimeout(() => {
      screenHs.classList.remove('locked');
      // Advance progress bar
      document.getElementById('uvStep1').classList.remove('active');
      document.getElementById('uvStep1').classList.add('done');
      document.getElementById('uvStep2').classList.add('active');
      // Update instruction
      instrText.innerHTML = 'Click the <strong>wavelength screen</strong> to set a wavelength (0–250 nm).';
      success.classList.add('hidden');
    }, 600);

    updateStep('UV-VIS Step 1 complete — Spectrophotometer turned ON');
  }

  // ── UV-VIS STEP 2: WAVELENGTH INPUT ───────────────────────────────────────
  let wlInput = ''; // raw string input from keypad

  function openWavelengthModal() {
    if (!spectroOn || wavelengthSet) return;
    document.getElementById('modalWavelength').classList.add('open');
    // Focus first key for keyboard input
    setTimeout(() => document.querySelector('.wl-key')?.focus(), 50);
  }
  function closeWavelengthModal() {
    document.getElementById('modalWavelength').classList.remove('open');
    if (!wavelengthSet) {
      // Reset input
      wlInput = '';
      currentWavelength = 0;
      updateWlDisplay();
      hideWlError();
    }
  }

  function updateWlDisplay() {
    const display = (wlInput === '') ? String(currentWavelength) : wlInput;
    document.getElementById('wlValue').textContent = display;
  }

  function pressKey(d) {
    if (wavelengthSet) return;
    hideWlError();
    // Limit to 3 digits to match max 250
    if (wlInput.length >= 3) return;
    // Avoid leading zeros (e.g. "00" → "0")
    if (wlInput === '0') wlInput = '';
    wlInput += d;
    currentWavelength = parseInt(wlInput, 10) || 0;
    updateWlDisplay();
    playClickTone(900, 600);
  }

  function backspaceKey() {
    if (wavelengthSet) return;
    hideWlError();
    if (wlInput.length > 0) {
      wlInput = wlInput.slice(0, -1);
      currentWavelength = parseInt(wlInput, 10) || 0;
      updateWlDisplay();
      playClickTone(500, 300);
    }
  }

  function clearWavelength() {
    if (wavelengthSet) return;
    wlInput = '';
    currentWavelength = 0;
    hideWlError();
    updateWlDisplay();
    playClickTone(500, 300);
  }

  // ── ARROW HOLD-TO-ACCELERATE ──
  let arrowInterval = null;
  let arrowTimeout  = null;
  function startArrow(dir) {
    if (wavelengthSet) return;
    hideWlError();
    // Tap once
    bumpWavelength(dir);

    let speed = 240;     // initial repeat rate ms
    let count = 0;
    arrowTimeout = setTimeout(function step() {
      bumpWavelength(dir);
      count++;
      // Accelerate over time
      if      (count > 20) speed = 40;
      else if (count > 10) speed = 80;
      else                 speed = 160;
      arrowInterval = setTimeout(step, speed);
    }, 400); // delay before auto-repeat starts
  }
  function stopArrow() {
    clearTimeout(arrowTimeout);
    clearTimeout(arrowInterval);
    arrowInterval = null;
    arrowTimeout = null;
  }
  function bumpWavelength(dir) {
    // Use currentWavelength (numeric) here, not wlInput
    let v = currentWavelength + dir;
    if (v < 0)   v = 0;
    if (v > 999) v = 999;       // hard cap on display, range check on confirm
    currentWavelength = v;
    wlInput = String(v);
    updateWlDisplay();
  }

  function showWlError(msg) {
    const err = document.getElementById('wlError');
    document.getElementById('wlErrorText').textContent = msg;
    err.classList.remove('hidden');
    document.getElementById('wlDisplay').classList.add('shake');
    setTimeout(() => document.getElementById('wlDisplay').classList.remove('shake'), 500);
  }
  function hideWlError() {
    document.getElementById('wlError').classList.add('hidden');
  }

  function confirmWavelength() {
    if (wavelengthSet) return;
    const v = currentWavelength;
    if (wlInput === '' && v === 0) {
      showWlError('Please enter a wavelength first.');
      return;
    }
    if (v < 0 || v > 250) {
      showWlError('Wavelength invalid. Please input a number between 0 – 250.');
      return;
    }

    // Success — lock in
    wavelengthSet = true;
    hideWlError();
    const confirmBtn = document.getElementById('wlConfirm');
    confirmBtn.classList.add('success');
    confirmBtn.textContent = '✓ Wavelength Set';
    document.getElementById('wlDisplay').classList.add('flash');
    playClickTone(800, 300);

    // Lock screen hotspot — done
    document.getElementById('screenHotspot').classList.add('locked');

    // Advance progress bar
    document.getElementById('uvStep2').classList.remove('active');
    document.getElementById('uvStep2').classList.add('done');
    document.getElementById('uvStep3').classList.add('active');

    // Update instruction + unlock lid hotspot
    document.getElementById('uvInstructionText').innerHTML =
      `✅ Wavelength set to <strong>${v} nm</strong>. Now click the <strong>compartment lid</strong> to open it.`;

    updateStep(`UV-VIS Step 2 complete — Wavelength set to ${v} nm`);

    // Pause showing the value, then close modal + unlock lid
    setTimeout(() => {
      document.getElementById('modalWavelength').classList.remove('open');
      document.getElementById('lidOpenHotspot').classList.remove('locked');
    }, 2000);
  }

  // ── UV-VIS STEP 3: PLACE BLANK CUVETTE ────────────────────────────────────
  let lidOpened       = false;
  let blankPlaced     = false;
  let blankLidClosed  = false;

  function openCompartmentLid() {
    // Branch: are we in Step 4 (after Set Zero)? If so, open with blank still inside for removal.
    if (zeroSet && !blankRemoved) {
      openCompartmentLidForRemoval();
      return;
    }

    if (lidOpened) return;
    lidOpened = true;

    const imgOn       = document.getElementById('spectroImgOn');
    const imgLidOpen  = document.getElementById('spectroImgLidOpen');
    const lidHotspot  = document.getElementById('lidOpenHotspot');
    const dropZone    = document.getElementById('cuvetteDropZone');
    const cuvetteSrc  = document.getElementById('cuvetteSource');

    // Click ripple at lid
    const ripple = document.createElement('div');
    ripple.className = 'ripple';
    ripple.style.left = '42.25%';
    ripple.style.top  = '57.6%';
    document.getElementById('spectroMachine').appendChild(ripple);
    setTimeout(() => ripple.remove(), 700);

    // Mark hotspot done
    lidHotspot.classList.add('done');
    playClickTone(500, 250);

    // Crossfade ON → LID OPEN
    imgOn.style.transition = 'opacity .25s';
    imgOn.style.opacity = '0';
    setTimeout(() => {
      imgOn.classList.add('hidden');
      imgOn.style.opacity = '';
      imgOn.style.transition = '';
      imgLidOpen.classList.remove('hidden');
      imgLidOpen.style.opacity = '0';
      imgLidOpen.style.transition = 'opacity .25s';
      requestAnimationFrame(() => { imgLidOpen.style.opacity = '1'; });
      setTimeout(() => { imgLidOpen.style.transition = ''; }, 300);
    }, 250);

    // Show drop zone and the draggable cuvette
    setTimeout(() => {
      dropZone.classList.remove('hidden');
      cuvetteSrc.classList.remove('hidden');
      document.getElementById('uvInstructionText').innerHTML =
        '🧪 <strong>Drag the blank cuvette</strong> into the open compartment.';
    }, 600);
  }

  // ── DRAG & DROP HANDLERS ──
  function cuvetteDragStart(e) {
    e.dataTransfer.setData('text/plain', e.target.dataset.type || 'blank');
    e.dataTransfer.effectAllowed = 'move';
    e.target.classList.add('dragging');
  }
  function cuvetteDragEnd(e) {
    e.target.classList.remove('dragging');
  }
  function cuvetteDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    e.currentTarget.classList.add('over');
  }
  function cuvetteDragLeave(e) {
    e.currentTarget.classList.remove('over');
  }
  function cuvetteDrop(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('over');
    const type = e.dataTransfer.getData('text/plain');

    // STEP 3 — placing blank
    if (type === 'blank' && !blankPlaced) {
      blankPlaced = true;

      const imgLidOpen   = document.getElementById('spectroImgLidOpen');
      const imgBlankIn   = document.getElementById('spectroImgBlankIn');
      const dropZone     = document.getElementById('cuvetteDropZone');
      const cuvetteSrc   = document.getElementById('cuvetteSource');
      const lidClose     = document.getElementById('lidCloseHotspot');

      dropZone.classList.add('hidden');
      cuvetteSrc.classList.add('hidden');
      playClickTone(700, 400);

      imgLidOpen.style.transition = 'opacity .25s';
      imgLidOpen.style.opacity = '0';
      setTimeout(() => {
        imgLidOpen.classList.add('hidden');
        imgLidOpen.style.opacity = '';
        imgLidOpen.style.transition = '';
        imgBlankIn.classList.remove('hidden');
        imgBlankIn.style.opacity = '0';
        imgBlankIn.style.transition = 'opacity .25s';
        requestAnimationFrame(() => { imgBlankIn.style.opacity = '1'; });
        setTimeout(() => { imgBlankIn.style.transition = ''; }, 300);
      }, 250);

      setTimeout(() => {
        lidClose.classList.remove('locked');
        document.getElementById('uvInstructionText').innerHTML =
          '🧪 Blank cuvette placed. Now <strong>click the lid</strong> to close it.';
      }, 600);
      return;
    }

    // STEP 5 — placing sample
    if (type === 'sample' && !samplePlaced && blankRemoved) {
      samplePlaced = true;

      const imgLidOpen   = document.getElementById('spectroImgLidOpen');
      const imgSampleIn  = document.getElementById('spectroImgSampleIn');
      const dropZone     = document.getElementById('cuvetteDropZone');
      const sampleSrc    = document.getElementById('sampleCuvetteSource');
      const sampleLidClose = document.getElementById('lidCloseSampleHotspot');

      dropZone.classList.add('hidden');
      sampleSrc.classList.add('hidden');
      playClickTone(700, 400);

      imgLidOpen.style.transition = 'opacity .25s';
      imgLidOpen.style.opacity = '0';
      setTimeout(() => {
        imgLidOpen.classList.add('hidden');
        imgLidOpen.style.opacity = '';
        imgLidOpen.style.transition = '';
        imgSampleIn.classList.remove('hidden');
        imgSampleIn.style.opacity = '0';
        imgSampleIn.style.transition = 'opacity .25s';
        requestAnimationFrame(() => { imgSampleIn.style.opacity = '1'; });
        setTimeout(() => { imgSampleIn.style.transition = ''; }, 300);
      }, 250);

      setTimeout(() => {
        // Reuse the close-lid hotspot
        const lidClose = document.getElementById('lidCloseHotspot');
        lidClose.classList.remove('locked');
        lidClose.classList.remove('done');
        // Re-bind to a sample-specific close handler via a flag
        document.getElementById('uvInstructionText').innerHTML =
          '🧪 Sample cuvette placed. Now <strong>click the lid</strong> to close it.';
      }, 600);
      return;
    }
  }

  function closeCompartmentLidWithBlank() {
    // Branch: closing on sample (Step 5)?
    if (samplePlaced && !sampleLidClosed) {
      closeCompartmentLidWithSample();
      return;
    }

    if (blankLidClosed) return;
    blankLidClosed = true;

    const imgBlankIn = document.getElementById('spectroImgBlankIn');
    const imgOn      = document.getElementById('spectroImgOn');
    const lidClose   = document.getElementById('lidCloseHotspot');

    // Click ripple
    const ripple = document.createElement('div');
    ripple.className = 'ripple';
    ripple.style.left = '42.25%';
    ripple.style.top  = '41.4%';
    document.getElementById('spectroMachine').appendChild(ripple);
    setTimeout(() => ripple.remove(), 700);

    lidClose.classList.add('done');
    playClickTone(500, 250);

    // Crossfade blankIn → ON
    imgBlankIn.style.transition = 'opacity .25s';
    imgBlankIn.style.opacity = '0';
    setTimeout(() => {
      imgBlankIn.classList.add('hidden');
      imgBlankIn.style.opacity = '';
      imgBlankIn.style.transition = '';
      imgOn.classList.remove('hidden');
      imgOn.style.opacity = '0';
      imgOn.style.transition = 'opacity .25s';
      requestAnimationFrame(() => { imgOn.style.opacity = '1'; });
      setTimeout(() => { imgOn.style.transition = ''; }, 300);
    }, 250);

    // Advance progress bar + unlock Step 4
    setTimeout(() => {
      document.getElementById('uvStep3').classList.remove('active');
      document.getElementById('uvStep3').classList.add('done');
      document.getElementById('uvStep4').classList.add('active');
      document.getElementById('setZeroHotspot').classList.remove('locked');
      document.getElementById('uvInstructionText').innerHTML =
        '🟢 Now click the <strong>SET ZERO</strong> button on the spectrophotometer to zero the instrument.';
    }, 600);

    updateStep('UV-VIS Step 3 complete — Blank cuvette placed');
  }

  // STEP 5 close — sample inside, close lid → unlock Read
  function closeCompartmentLidWithSample() {
    if (sampleLidClosed) return;
    sampleLidClosed = true;

    const imgSampleIn = document.getElementById('spectroImgSampleIn');
    const imgOn       = document.getElementById('spectroImgOn');
    const lidClose    = document.getElementById('lidCloseHotspot');

    const ripple = document.createElement('div');
    ripple.className = 'ripple';
    ripple.style.left = '42.25%';
    ripple.style.top  = '41.4%';
    document.getElementById('spectroMachine').appendChild(ripple);
    setTimeout(() => ripple.remove(), 700);

    lidClose.classList.add('done');
    playClickTone(500, 250);

    imgSampleIn.style.transition = 'opacity .25s';
    imgSampleIn.style.opacity = '0';
    setTimeout(() => {
      imgSampleIn.classList.add('hidden');
      imgSampleIn.style.opacity = '';
      imgSampleIn.style.transition = '';
      imgOn.classList.remove('hidden');
      imgOn.style.opacity = '0';
      imgOn.style.transition = 'opacity .25s';
      requestAnimationFrame(() => { imgOn.style.opacity = '1'; });
      setTimeout(() => { imgOn.style.transition = ''; }, 300);
    }, 250);

    // Advance to Step 6 — unlock Read Absorbance
    setTimeout(() => {
      document.getElementById('uvStep5').classList.remove('active');
      document.getElementById('uvStep5').classList.add('done');
      document.getElementById('uvStep6').classList.add('active');
      document.getElementById('readHotspot').classList.remove('locked');
      document.getElementById('uvInstructionText').innerHTML =
        '🟣 Click the <strong>READ ABS</strong> button to measure the absorbance.';
    }, 600);

    updateStep('UV-VIS Step 5 complete — Sample placed');
  }

  // ── UV-VIS STEP 4: SET ZERO + REMOVE BLANK ────────────────────────────────
  let zeroSet         = false;
  let blankRemoved    = false;
  let removeLidOpened = false;

  function pressSetZero() {
    if (zeroSet) return;
    zeroSet = true;

    const setZeroHs = document.getElementById('setZeroHotspot');
    const lidHs     = document.getElementById('lidOpenHotspot');

    // Click ripple at set zero
    const ripple = document.createElement('div');
    ripple.className = 'ripple';
    ripple.style.left = '51.5%';
    ripple.style.top  = '67.2%';
    document.getElementById('spectroMachine').appendChild(ripple);
    setTimeout(() => ripple.remove(), 700);

    setZeroHs.classList.add('done');
    playClickTone(900, 600);

    // Flash the modal
    const modal = document.querySelector('#modalUvvis .lab-modal');
    modal.classList.add('zero-flash');
    setTimeout(() => modal.classList.remove('zero-flash'), 1200);

    // Show toast notification
    const toast = document.getElementById('zeroToast');
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2400);

    // Update instruction → unlock the lid hotspot again so user can re-open compartment
    setTimeout(() => {
      document.getElementById('uvInstructionText').innerHTML =
        '✅ Instrument set to zero. Now click the <strong>compartment lid</strong> to re-open it and remove the blank.';
      // Re-unlock the lid open hotspot (it was 'done' before)
      lidHs.classList.remove('done');
      lidHs.classList.remove('locked');
      // Mark that we're in remove-blank mode now
      removeLidOpened = false;
    }, 800);
  }

  // Override: when in Step 4, opening the lid should show blank-in state (not empty)
  // and unlock the remove-blank hotspot
  function openCompartmentLidForRemoval() {
    if (removeLidOpened) return;
    removeLidOpened = true;

    const imgOn       = document.getElementById('spectroImgOn');
    const imgBlankIn  = document.getElementById('spectroImgBlankIn');
    const lidHs       = document.getElementById('lidOpenHotspot');
    const removeHs    = document.getElementById('removeBlankHotspot');

    // Click ripple
    const ripple = document.createElement('div');
    ripple.className = 'ripple';
    ripple.style.left = '42.25%';
    ripple.style.top  = '57.6%';
    document.getElementById('spectroMachine').appendChild(ripple);
    setTimeout(() => ripple.remove(), 700);

    lidHs.classList.add('done');
    playClickTone(500, 250);

    // Crossfade ON → BLANK IN (open lid showing blank inside)
    imgOn.style.transition = 'opacity .25s';
    imgOn.style.opacity = '0';
    setTimeout(() => {
      imgOn.classList.add('hidden');
      imgOn.style.opacity = '';
      imgOn.style.transition = '';
      imgBlankIn.classList.remove('hidden');
      imgBlankIn.style.opacity = '0';
      imgBlankIn.style.transition = 'opacity .25s';
      requestAnimationFrame(() => { imgBlankIn.style.opacity = '1'; });
      setTimeout(() => { imgBlankIn.style.transition = ''; }, 300);
    }, 250);

    // Unlock the remove-blank hotspot
    setTimeout(() => {
      removeHs.classList.remove('locked');
      document.getElementById('uvInstructionText').innerHTML =
        '🧪 Click the <strong>blank cuvette</strong> in the compartment to remove it.';
    }, 600);
  }

  function removeBlankCuvette() {
    if (blankRemoved) return;
    blankRemoved = true;

    const imgBlankIn  = document.getElementById('spectroImgBlankIn');
    const imgLidOpen  = document.getElementById('spectroImgLidOpen');
    const removeHs    = document.getElementById('removeBlankHotspot');

    // Click ripple
    const ripple = document.createElement('div');
    ripple.className = 'ripple';
    ripple.style.left = '42.25%';
    ripple.style.top  = '57.6%';
    document.getElementById('spectroMachine').appendChild(ripple);
    setTimeout(() => ripple.remove(), 700);

    removeHs.classList.add('done');
    playClickTone(700, 350);

    // Crossfade BLANK IN → LID OPEN (now empty)
    imgBlankIn.style.transition = 'opacity .25s';
    imgBlankIn.style.opacity = '0';
    setTimeout(() => {
      imgBlankIn.classList.add('hidden');
      imgBlankIn.style.opacity = '';
      imgBlankIn.style.transition = '';
      imgLidOpen.classList.remove('hidden');
      imgLidOpen.style.opacity = '0';
      imgLidOpen.style.transition = 'opacity .25s';
      requestAnimationFrame(() => { imgLidOpen.style.opacity = '1'; });
      setTimeout(() => { imgLidOpen.style.transition = ''; }, 300);
    }, 250);

    // Mark Step 4 complete + unlock Step 5
    setTimeout(() => {
      document.getElementById('uvStep4').classList.remove('active');
      document.getElementById('uvStep4').classList.add('done');
      document.getElementById('uvStep5').classList.add('active');
      // Show sample cuvette tray + drop zone
      document.getElementById('sampleCuvetteSource').classList.remove('hidden');
      document.getElementById('cuvetteDropZone').classList.remove('hidden');
      document.getElementById('uvInstructionText').innerHTML =
        '🧪 <strong>Drag the sample cuvette</strong> into the open compartment.';
    }, 600);

    updateStep('UV-VIS Step 4 complete — Instrument zeroed and blank removed');
  }

  // ── UV-VIS STEPS 5 & 6: PLACE SAMPLE + READ ABSORBANCE ────────────────────
  let samplePlaced     = false;
  let sampleLidClosed  = false;
  let readDone         = false;

  // ───────── ABSORBANCE FORMULA — change this to adjust the reading range ─────────
  // Default: random value between 0.70 and 0.90 with 2 decimal places.
  // Examples:
  //   0.10–0.30 → (Math.random() * 0.2 + 0.1).toFixed(2)
  //   0.50–1.50 → (Math.random() * 1.0 + 0.5).toFixed(2)
  //   0.00–2.00 → (Math.random() * 2.0).toFixed(2)
  function generateAbsorbanceReading() {
    return (Math.random() * 0.2 + 0.7).toFixed(2);
  }
  // ──────────────────────────────────────────────────────────────────────────

  function pressReadAbsorbance() {
    if (readDone) return;
    readDone = true;

    const readHs = document.getElementById('readHotspot');

    // Click ripple
    const ripple = document.createElement('div');
    ripple.className = 'ripple';
    ripple.style.left = '59.6%';
    ripple.style.top  = '66.8%';
    document.getElementById('spectroMachine').appendChild(ripple);
    setTimeout(() => ripple.remove(), 700);

    readHs.classList.add('done');
    playClickTone(900, 700);

    // Open absorbance modal
    document.getElementById('modalAbsorbance').classList.add('open');

    // Set wavelength info
    document.getElementById('absInfoWavelength').textContent = `${currentWavelength} nm`;

    // Reset modal to reading state
    document.getElementById('absReadingPane').classList.remove('hidden');
    document.getElementById('absCompletionPane').classList.add('hidden');
    document.getElementById('absCloseBtn').style.display = 'none';
    document.getElementById('absModalTitle').textContent = 'Reading Absorbance';
    const absValueEl = document.getElementById('absValue');
    absValueEl.classList.add('reading');
    document.getElementById('absStatusText').textContent = 'Reading sample…';

    // Animate the reading — show random scrolling values for ~2 seconds
    let ticks = 0;
    const scrollInterval = setInterval(() => {
      absValueEl.textContent = (Math.random() * 0.99).toFixed(2);
      ticks++;
      if (ticks > 18) {
        clearInterval(scrollInterval);
        // Final value
        const finalValue = generateAbsorbanceReading();
        absValueEl.textContent = finalValue;
        absValueEl.classList.remove('reading');
        document.getElementById('absStatusText').textContent = 'Reading complete.';

        // After a short pause, show completion celebration
        setTimeout(() => {
          document.getElementById('absModalTitle').textContent = 'Absorbance: ' + finalValue;
          document.getElementById('absCompletionPane').classList.remove('hidden');
          document.getElementById('absCloseBtn').style.display = 'flex';
          // Mark Step 6 done
          document.getElementById('uvStep6').classList.remove('active');
          document.getElementById('uvStep6').classList.add('done');
          document.getElementById('uvInstructionText').innerHTML =
            `🎉 <strong>Simulation complete!</strong> Absorbance reading: <strong>${finalValue}</strong>`;
          playClickTone(700, 1200);
          // Flag completion so the congrats modal opens when the user closes this one
          window.uvvisFullyCompleted = true;
        }, 1400);
      }
    }, 100);

    updateStep('UV-VIS Step 6 — Reading absorbance…');
  }

  // ── CONGRATS MODAL state (declared early for safety) ──
  window.uvvisFullyCompleted = false;

  function closeAbsorbanceModal() {
    const absModal = document.getElementById('modalAbsorbance');
    absModal.classList.remove('open');

    // If UV-VIS just hit completion, fire the congrats modal a beat after closing.
    // Use window-scoped flag so it's reliable even if function is called early.
    if (window.uvvisFullyCompleted) {
      window.uvvisFullyCompleted = false;
      setTimeout(() => {
        const congrats = document.getElementById('modalCongrats');
        if (congrats) congrats.classList.add('open');
      }, 350);
    }
  }

  function closeCongratsModal() {
    document.getElementById('modalCongrats').classList.remove('open');
  }

  function runAgainFromCongrats() {
    // Close the congrats modal first
    document.getElementById('modalCongrats').classList.remove('open');
    // Reset BOTH tools
    window.dispatchEvent(new CustomEvent('labReset'));
    // Re-lock UV-VIS since dissolution hasn't been completed in the new run
    dissolutionComplete = false;
    // Also close any modals that may be open
    document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('open'));
  }

  // Allow ESC to close the congrats modal
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && document.getElementById('modalCongrats')?.classList.contains('open')) {
      closeCongratsModal();
    }
  });

  // Keyboard input on wavelength modal
  document.addEventListener('keydown', e => {
    const wlOpen = document.getElementById('modalWavelength')?.classList.contains('open');
    if (!wlOpen || wavelengthSet) return;
    if (/^[0-9]$/.test(e.key))           pressKey(e.key);
    else if (e.key === 'Backspace')      backspaceKey();
    else if (e.key === 'Enter')          confirmWavelength();
    else if (e.key === 'ArrowLeft')      bumpWavelength(-1);
    else if (e.key === 'ArrowRight')     bumpWavelength(1);
  });

  // ── SHOW LABELS TOGGLE ──────────────────────────────────────────────────
  function toggleLabels() {
    document.getElementById('labelsOverlay').classList.toggle('open');
  }

  // ── SHARED CLICK SOUND ──────────────────────────────────────────────────
  function playClickTone(startHz, endHz) {
    if (isMuted) return;
    try {
      const ctx  = new (window.AudioContext || window.webkitAudioContext)();
      const osc  = ctx.createOscillator();
      const gain = ctx.createGain();
      osc.connect(gain); gain.connect(ctx.destination);
      osc.frequency.setValueAtTime(startHz, ctx.currentTime);
      osc.frequency.exponentialRampToValueAtTime(endHz, ctx.currentTime + .08);
      gain.gain.setValueAtTime(.25, ctx.currentTime);
      gain.gain.exponentialRampToValueAtTime(.001, ctx.currentTime + .1);
      osc.start(); osc.stop(ctx.currentTime + .1);
    } catch(e) {}
  }

  // Restart only the UV-VIS tool (keep modal open)
  function uvRestartTool() {
    window.dispatchEvent(new CustomEvent('labResetUvvis'));
    document.getElementById('modalUvvis')?.classList.add('open');
    document.getElementById('modalWavelength')?.classList.remove('open');
    document.getElementById('modalAbsorbance')?.classList.remove('open');
    document.getElementById('labelsOverlay')?.classList.remove('open');
  }

  // Hook into lab reset (global) — clears BOTH tools
  window.addEventListener('labReset', () => {
    // Fire both scoped events so each tool clears its own state
    window.dispatchEvent(new CustomEvent('labResetUvvis'));
    window.dispatchEvent(new CustomEvent('labResetDissolution'));
  });

  // Hook into UV-VIS-only reset
  window.addEventListener('labResetUvvis', () => {
    spectroOn = false;
    wavelengthSet = false;
    currentWavelength = 0;
    wlInput = '';
    window.uvvisFullyCompleted = false;

    const hotspot   = document.getElementById('powerHotspot');
    const screenHs  = document.getElementById('screenHotspot');
    const imgOff    = document.getElementById('spectroImgOff');
    const imgOn     = document.getElementById('spectroImgOn');
    const success   = document.getElementById('stepSuccess');
    const instr     = document.getElementById('uvInstructionText');
    if (!hotspot) return;

    hotspot.classList.remove('done');
    screenHs.classList.add('locked');
    screenHs.classList.remove('done');
    imgOff.classList.remove('hidden');
    imgOn.classList.add('hidden');
    success.classList.add('hidden');
    instr.innerHTML = 'Click the <strong>power button</strong> on the spectrophotometer to turn it on.';

    // Reset progress bar
    document.getElementById('uvStep1').classList.add('active');
    document.getElementById('uvStep1').classList.remove('done');
    document.getElementById('uvStep2').classList.remove('active', 'done');
    document.getElementById('uvStep3').classList.remove('active', 'done');
    document.getElementById('uvStep4').classList.remove('active', 'done');
    document.getElementById('uvStep5').classList.remove('active', 'done');
    document.getElementById('uvStep6').classList.remove('active', 'done');

    // Reset Step 3 state
    lidOpened      = false;
    blankPlaced    = false;
    blankLidClosed = false;
    document.getElementById('lidOpenHotspot').classList.add('locked');
    document.getElementById('lidOpenHotspot').classList.remove('done');
    document.getElementById('lidCloseHotspot').classList.add('locked');
    document.getElementById('lidCloseHotspot').classList.remove('done');
    document.getElementById('spectroImgLidOpen').classList.add('hidden');
    document.getElementById('spectroImgBlankIn').classList.add('hidden');
    document.getElementById('spectroImgSampleIn').classList.add('hidden');
    document.getElementById('cuvetteSource').classList.add('hidden');
    document.getElementById('sampleCuvetteSource').classList.add('hidden');
    document.getElementById('cuvetteDropZone').classList.add('hidden');

    // Reset Step 4 state
    zeroSet         = false;
    blankRemoved    = false;
    removeLidOpened = false;
    document.getElementById('setZeroHotspot').classList.add('locked');
    document.getElementById('setZeroHotspot').classList.remove('done');
    document.getElementById('removeBlankHotspot').classList.add('locked');
    document.getElementById('removeBlankHotspot').classList.remove('done');
    document.getElementById('zeroToast').classList.remove('show');

    // Reset Step 5/6 state
    samplePlaced    = false;
    sampleLidClosed = false;
    readDone        = false;
    document.getElementById('readHotspot').classList.add('locked');
    document.getElementById('readHotspot').classList.remove('done');
    document.getElementById('modalAbsorbance').classList.remove('open');

    // Note: dissolution apparatus state is reset by its own labReset listener in dissolution.php

    // Reset wavelength modal state
    const confirmBtn = document.getElementById('wlConfirm');
    confirmBtn.classList.remove('success');
    confirmBtn.textContent = 'Confirm Wavelength';
    document.getElementById('wlValue').textContent = '0';
    document.getElementById('wlError').classList.add('hidden');
    document.getElementById('modalWavelength').classList.remove('open');
    document.getElementById('labelsOverlay').classList.remove('open');

    // Reset the global step indicator at the top of the lab
    updateStep('Step 1 — Select a tool from the toolbar');
  });

  // ── KEYBOARD ──────────────────────────────────────────────────────────────
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      // Close labels overlay or wavelength modal first, otherwise all
      if (document.getElementById('labelsOverlay').classList.contains('open')) {
        toggleLabels();
        return;
      }
      if (document.getElementById('modalWavelength').classList.contains('open')) {
        closeWavelengthModal();
        return;
      }
      closeAllModals();
    }
    if (e.key === 'm' || e.key === 'M') toggleMute();
  });
  </script>
</body>
</html>
