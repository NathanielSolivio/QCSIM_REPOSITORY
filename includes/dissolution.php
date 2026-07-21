<!-- 
 Credits: 
 Creators: Nathaniel P. Solivio, Jae Marianne Almazan and Hayya Michaela Cajuday
 Programmed/Written by: Nathaniel P. Solivio
 Tested by: Nathaniel P. Solivio, Jae Marianne Almazan and Hayya Michaela Cajuday
 Design by: Nathaniel P. Solivio, Jae Marianne Almazan and Hayya Michaela Cajuday
-->

<style>
/* ══════════════════════════════════════════════════════════════════════════
   DISSOLUTION APPARATUS
   ══════════════════════════════════════════════════════════════════════════ */

.ds-scene {
  position: relative;
  width: 100%;
  max-width: 800px;
  margin: 0 auto;
  aspect-ratio: 1200 / 750;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 16px rgba(13,45,78,.12);
  user-select: none;
}

.ds-scene-bg {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  -webkit-user-drag: none;
}

/* ── LAYERED OBJECTS */
.ds-obj {
  position: absolute;
  transform: translate(-50%, -50%);
  z-index: 5;
  pointer-events: none;
}
.ds-obj img {
  display: block;
  width: 100%;
  height: 100%;
  pointer-events: none;
  -webkit-user-drag: none;
  user-select: none;
}

/* ── ROUND FLASK (draggable in Step 1) ──
   PNG dimensions: 240×320 */
.ds-round {
  left: 55.5%;
  top:  50.53%;
  width: 20%;
  height: 42.67%;
  pointer-events: auto;
  cursor: grab;
  z-index: 10;
  transform-origin: 50% 50%;
  transition: transform .4s ease-out;
}
.ds-round:active   { cursor: grabbing; }
.ds-round.dragging { opacity: .35; }

/* Pour pose: raised up to align spout with volumetric flask opening.
   Δy = -566.5px / 750 = -75.53% */
.ds-round.tilted {
  transform: translate(-50%, -50%) translate(-4.54%, -75.53%) rotate(109deg);
}

.ds-round .ds-round-liquid,
.ds-round .ds-round-outline {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
}
.ds-round .ds-round-liquid {
  clip-path: inset(0% 0 0 0);
  transition: clip-path .12s linear;
  z-index: 1;
  opacity: 0.5;
}
.ds-round .ds-round-outline { z-index: 2; }

.ds-round-pulse {
  position: absolute;
  inset: -6%;
  border-radius: 50%;
  border: 3px solid rgba(59,130,246,.7);
  pointer-events: none;
  animation: dsRingPulse 1.6s ease-out infinite;
  z-index: 3;
}
.ds-round-pulse.delay { animation-delay: .8s; }
.ds-round.dragging .ds-round-pulse { display: none; }
.ds-round.armed     .ds-round-pulse { display: none; }
.ds-round.done      .ds-round-pulse { display: none; }
.ds-round.done {
  cursor: default;
  opacity: .6;
}
@keyframes dsRingPulse {
  0%   { transform: scale(.85); opacity: 1; }
  100% { transform: scale(1.25); opacity: 0; }
}

/* ── VOLUMETRIC FLASK ──
   PNG dimensions: 80×320 */
.ds-vol {
  left: 67.25%;
  top:  49.87%;
  width: 6.67%;
  height: 42.67%;
  z-index: 8;
}
.ds-vol-img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  z-index: 2;
}
.ds-vol-liquid-svg {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  z-index: 1;
  opacity: 0.5;
}
.ds-vol-bands {
  position: absolute;
  inset: 0;
  z-index: 3;
  pointer-events: none;
}
.ds-vol-band {
  position: absolute;
  left: 8%;
  right: 8%;
  height: 2px;
  background: #dc2626;
}
.ds-vol-band-75 { top: 28%; }
.ds-vol-band-70 {
  top: 32%;
  height: 1.5px;
  opacity: .65;
  background-image: linear-gradient(to right, #dc2626 50%, transparent 50%);
  background-size: 5px 100%;
  background-color: transparent;
}
.ds-vol-band-label {
  position: absolute;
  font-size: 9px;
  font-weight: 800;
  color: #dc2626;
  font-family: sans-serif;
  white-space: nowrap;
  background: rgba(255,255,255,.85);
  padding: 0 3px;
  border-radius: 3px;
  z-index: 4;
}
.ds-vol-band-label.l75 { right: -28px; top: 25%; }
.ds-vol-band-label.l70 { right: -28px; top: 30%; opacity: .75; font-weight: 600; }

/* ── DROP ZONE  */
.ds-drop-zone {
  position: absolute;
  left: 67.25%;
  top:  49.87%;
  transform: translate(-50%, -50%);
  width: 12%;
  height: 50%;
  border: 2.5px dashed transparent;
  border-radius: 6px;
  background: transparent;
  z-index: 12;
  pointer-events: auto;
  transition: all .2s;
}
.ds-drop-zone.over {
  border-color: #16a34a;
  background: rgba(34,197,94, .25);
  transform: translate(-50%, -50%) scale(1.05);
}

/* ── TABLET CONTAINER (clickable in Step 3) ──
   PNG dimensions: 60×80 */
.ds-tablet {
  left: 75.67%;
  top:  64.4%;
  width: 5%;
  height: 10.67%;
  pointer-events: none;
  cursor: default;
}
.ds-tablet.armed {
  pointer-events: auto;
  cursor: pointer;
  z-index: 11;
}
.ds-tablet img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
}

.ds-tablet-pulse {
  position: absolute;
  inset: -8%;
  border-radius: 12%;
  border: 3px solid rgba(217, 119, 6, .8);
  pointer-events: none;
  animation: dsRingPulse 1.6s ease-out infinite;
  z-index: 3;
  display: none;
}
.ds-tablet-pulse.delay { animation-delay: .8s; }
.ds-tablet.armed .ds-tablet-pulse { display: block; }
.ds-tablet.opened .ds-tablet-pulse { display: none; }

/* ── DRAGGABLE TABLET (Step 4) ──
   PNG dimensions: 35×18 */
.ds-tablet-draggable {
  left: 75.67%;
  top:  61.33%;
  width: 2.92%;
  height: 2.4%;
  pointer-events: auto;
  cursor: grab;
  z-index: 13;
  animation: tabletBob 1.6s ease-in-out infinite;
}
.ds-tablet-draggable:active { cursor: grabbing; }
.ds-tablet-draggable.dragging { opacity: .35; }
@keyframes tabletBob {
  0%, 100% { transform: translate(-50%, -50%); }
  50%       { transform: translate(-50%, calc(-50% - 4px)); }
}
.ds-tablet-draggable img {
  width: 100%;
  height: 100%;
}

/* ── FALLING TABLET (Step 4 — descends to chamber bottom) ── */
.ds-tablet-falling {
  width: 2.92%;
  height: 2.4%;
  z-index: 6;                        
  pointer-events: none;
}
.ds-tablet-falling img {
  width: 100%;
  height: 100%;
}

/* ── TABLET DROP ZONE (over chamber, Step 4 only) ── */
.ds-tablet-drop-zone {
  left: 29.42%;
  top:  51.53%;
  width: 16%;
  height: 32%;
  display: none;
}
.ds-tablet-drop-zone.show { display: flex; }

/* ══ STEP 5: RPM HOTSPOTS */
.ds-rpm-hotspot {
  position: absolute;
  transform: translate(-50%, -50%);
  cursor: pointer;
  z-index: 11;
  border-radius: 6px;
  pointer-events: auto;
  transition: background .2s;
}
.ds-rpm-hotspot:hover {
  background: rgba(26,107,181,.18);
}
.ds-rpm-hotspot.locked {
  pointer-events: none;
  cursor: default;
}
.ds-rpm-hotspot.locked .ds-rpm-pulse { display: none; }
.ds-rpm-hotspot.done {
  pointer-events: none;
}
.ds-rpm-hotspot.done .ds-rpm-pulse { display: none; }

.ds-rpm-hotspot-numpad {
  left: 35.79%;
  top:  24.6%;
  width: 10.42%;
  height: 18.8%;
}
.ds-rpm-hotspot-screen {
  left: 23.38%;
  top:  17.6%;
  width: 11.58%;
  height: 3.73%;
}

.ds-rpm-pulse {
  position: absolute;
  inset: 0;
  border-radius: 6px;
  border: 3px solid rgba(26,107,181,.7);
  pointer-events: none;
  animation: dsRingPulse 1.6s ease-out infinite;
}
.ds-rpm-pulse.delay { animation-delay: .8s; }

/* RPM display overlay on the screen — same coords as screen hotspot */
.ds-rpm-display {
  position: absolute;
  left: 23.38%;
  top:  17.6%;
  transform: translate(-50%, -50%);
  width: 11.58%;
  height: 3.73%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Courier New', monospace;
  font-weight: 800;
  color: #4ade80;
  text-shadow: 0 0 6px rgba(74,222,128,.8);
  font-size: clamp(9px, 1.8vw, 18px);
  letter-spacing: 2px;
  z-index: 10;
  pointer-events: none;
  opacity: 0;
  transition: opacity .35s;
}
.ds-rpm-display.show {
  opacity: 1;
}

/* ══ STEP 6: TIME KNOB HOTSPOT ═════════════════ */
.ds-time-hotspot {
  position: absolute;
  left: 18.71%;
  top:  31.67%;
  transform: translate(-50%, -50%);
  width: 1.75%;
  height: 3.33%;
  cursor: pointer;
  z-index: 11;
  border-radius: 50%;
  pointer-events: auto;
  transition: background .2s;
}
.ds-time-hotspot:hover { background: rgba(168, 85, 247, .25); }
.ds-time-hotspot.locked {
  pointer-events: none;
  cursor: default;
}
.ds-time-hotspot.locked .ds-time-pulse { display: none; }
.ds-time-hotspot.done { pointer-events: none; }
.ds-time-hotspot.done .ds-time-pulse { display: none; }

.ds-time-pulse {
  position: absolute;
  inset: -100%;
  border-radius: 50%;
  border: 3px solid rgba(168, 85, 247, .85);
  pointer-events: none;
  animation: dsRingPulse 1.6s ease-out infinite;
}
.ds-time-pulse.delay { animation-delay: .8s; }

/* Time display overlay */
.ds-time-display {
  position: absolute;
  left: 23.25%;
  top:  26.4%;
  transform: translate(-50%, -50%);
  width: 12%;
  height: 3.73%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  font-family: 'Courier New', monospace;
  font-weight: 800;
  color: #4ade80;
  text-shadow: 0 0 6px rgba(74,222,128,.8);
  font-size: clamp(9px, 1.8vw, 18px);
  letter-spacing: 2px;
  z-index: 10;
  pointer-events: none;
  opacity: 0;
  transition: opacity .35s;
}
.ds-time-display.show { opacity: 1; }
.ds-time-display .ds-time-unit {
  font-size: .65em;
  opacity: .8;
}

/* ══ STEP 7: PADDLE ══
   Paddle PNG: 50×32 */
.ds-paddle {
  position: absolute;
  left: 26.92%;
  top:  54.27%;
  width: 4.17%;
  height: 4.27%;
  transform-origin: 50% 0%;        /* pivot at top-center of the blade */
  perspective: 200px;              /* enables 3D rotation feel */
  z-index: 5;
  pointer-events: none;
}
.ds-paddle img {
  display: block;
  width: 100%;
  height: 100%;
  pointer-events: none;
  -webkit-user-drag: none;
  user-select: none;
  transform-origin: 50% 0%;
}
.ds-paddle.spinning img {
  animation: paddleSpin var(--paddle-period, 1s) linear infinite;
}
@keyframes paddleSpin {
  from { transform: rotateY(0deg);   }
  to   { transform: rotateY(360deg); }
}

/* ══ START BUTTON HOTSPOT ══ */
.ds-start-hotspot {
  position: absolute;
  left: 25.21%;
  top:  31.73%;
  transform: translate(-50%, -50%);
  width: 7.92%;
  height: 3.2%;
  cursor: pointer;
  z-index: 11;
  border-radius: 4px;
  pointer-events: auto;
  transition: background .2s;
}
.ds-start-hotspot:hover { background: rgba(34,197,94,.18); }
.ds-start-hotspot.locked {
  pointer-events: none;
  cursor: default;
}
.ds-start-hotspot.locked .ds-start-pulse { display: none; }
.ds-start-hotspot.done { pointer-events: none; }
.ds-start-hotspot.done .ds-start-pulse { display: none; }

.ds-start-pulse {
  position: absolute;
  inset: 0;
  border-radius: 4px;
  border: 3px solid rgba(34,197,94,.85);
  pointer-events: none;
  animation: dsRingPulse 1.6s ease-out infinite;
}
.ds-start-pulse.delay { animation-delay: .8s; }

/* ══ TIME MODAL — knob + arrows ══ */
.ds-knob-row {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
  margin-bottom: 14px;
}
.ds-knob {
  width: 96px;
  height: 96px;
  border-radius: 50%;
  background: radial-gradient(circle at 30% 30%, #4a5568, #1a202c);
  position: relative;
  box-shadow: 0 6px 18px rgba(0,0,0,.3), inset 0 -3px 8px rgba(0,0,0,.4), inset 0 3px 6px rgba(255,255,255,.15);
  border: 3px solid #2d3748;
  user-select: none;
}
.ds-knob::before {
  /* Indicator notch — rotates with knob */
  content: '';
  position: absolute;
  top: 8px;
  left: 50%;
  transform: translateX(-50%);
  width: 6px;
  height: 18px;
  background: #4ade80;
  border-radius: 3px;
  box-shadow: 0 0 6px rgba(74,222,128,.8);
}
.ds-knob {
  transition: transform .2s ease-out;
}

.ds-knob-arrow {
  width: 56px;
  height: 56px;
  border-radius: 50%;
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
.ds-knob-arrow:hover {
  background: var(--primary, #1a6bb5);
  color: #fff;
  border-color: var(--primary, #1a6bb5);
}
.ds-knob-arrow:active { transform: scale(.94); }
.ds-knob-arrow.disabled {
  opacity: .35;
  cursor: not-allowed;
  pointer-events: none;
}

.ds-time-readout {
  text-align: center;
  background: linear-gradient(180deg, #0a1f10 0%, #0e2c17 100%);
  border: 2px solid var(--border, #d1e4f5);
  border-radius: 12px;
  padding: 16px 20px;
  margin-bottom: 14px;
  box-shadow: inset 0 2px 8px rgba(0,0,0,.4);
}
.ds-time-readout-label {
  font-family: 'Courier New', monospace;
  font-size: 11px;
  font-weight: 700;
  color: rgba(74,222,128,.7);
  letter-spacing: 2px;
  text-transform: uppercase;
  margin-bottom: 6px;
}
.ds-time-readout-value {
  font-family: 'Courier New', monospace;
  font-size: 36px;
  font-weight: 800;
  color: #4ade80;
  letter-spacing: 3px;
  text-shadow: 0 0 12px rgba(74,222,128,.7);
  line-height: 1;
}
.ds-time-readout-value .unit {
  font-size: 18px;
  opacity: .75;
  margin-left: 4px;
}
.ds-time-readout.shake { animation: shake .4s ease; border-color: #e53e3e; }
.ds-time-readout.flash { animation: flash .5s ease; }

/* ── CHAMBER FILLED (PNG overlay, hidden until Step 2 completes) ──
   PNG dimensions: 141×174 */
.ds-chamber-filled {
  left: 29.42%;
  top:  51.53%;
  width: 11.75%;
  height: 23.2%;
  z-index: 4;
  clip-path: inset(100% 0 0 0);
  transition: clip-path 1.4s ease-out;
  opacity: 0.5;
}
.ds-chamber-filled.filling {
  clip-path: inset(0% 0 0 0);          /* fully visible when filling */
}

/* ── CHAMBER DROP ZONE (Step 2 only) ──
   Positioned on the chamber center, larger for easy drop targeting */
.ds-chamber-drop-zone {
  left: 29.42%;
  top:  51.53%;
  width: 16%;
  height: 32%;
  display: none;
}
.ds-chamber-drop-zone.show { display: flex; }

/* ── ROUND FLASK CHAMBER POUR POSE ── */
.ds-round.tilted-chamber {
  transform: translate(-50%, -50%) translate(-29.67%, -56.5%) rotate(-109deg);
}

/* ── INLINE POUR CONTROLS ── */
.ds-inline-controls {
  display: none;
  margin-top: 14px;
  padding: 14px 16px;
  background: var(--primary-light, #e8f3fc);
  border: 1.5px solid var(--border, #d1e4f5);
  border-radius: 12px;
}
.ds-inline-controls.show { display: block; }

.ds-inline-controls-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
  gap: 10px;
  flex-wrap: wrap;
}
.ds-inline-controls-header span {
  font-size: 13px;
  font-weight: 700;
  color: var(--text, #0d2d4e);
}
.ds-level-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: #fff;
  border: 1.5px solid var(--border, #d1e4f5);
  padding: 4px 12px;
  border-radius: 99px;
  font-family: 'Courier New', monospace;
  font-size: 14px;
  font-weight: 700;
  color: var(--primary, #1a6bb5);
}
.ds-level-pill.in-range { color: #16a34a; border-color: #86efac; background: #f0fdf4; }
.ds-level-pill.over     { color: #dc2626; border-color: #fecaca; background: #fef2f2; }

.ds-controls {
  display: flex;
  gap: 10px;
}
.ds-pour-btn {
  flex: 1;
  user-select: none;
  cursor: pointer;
}
.ds-pour-btn:active { transform: scale(.97); }
.ds-pour-btn.pouring {
  background: #16a34a;
  animation: dsPourPulse .6s ease-in-out infinite alternate;
}
@keyframes dsPourPulse {
  from { box-shadow: 0 4px 14px rgba(22,163,74,.3); }
  to   { box-shadow: 0 6px 20px rgba(22,163,74,.6); }
}
/* ══ MODAL HEADER ACTIONS (Restart + Close) ══════════════════════════════ */
.modal-header-actions {
  display: flex;
  align-items: center;
  gap: 8px;
}
.ds-restart-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: #fff7ed;
  border: 1.5px solid #fdba74;
  color: #c2410c;
  font-family: 'Nunito', sans-serif;
  font-weight: 700;
  font-size: 12px;
  padding: 6px 12px;
  border-radius: 99px;
  cursor: pointer;
  transition: all .2s;
}
.ds-restart-btn:hover {
  background: #c2410c;
  color: #fff;
  border-color: #c2410c;
}
.ds-restart-btn:active { transform: scale(.96); }

/* ══ STEP 8: PIPETTE RACK + PIPETTE + FILTER */
.ds-rack-hotspot {
  position: absolute;
  left: 89.4%;
  top:  55.5%;
  transform: translate(-50%, -50%);
  width: 18.42%;
  height: 26%;
  cursor: pointer;
  z-index: 11;
  border-radius: 8px;
  pointer-events: auto;
  transition: background .2s;
}
.ds-rack-hotspot:hover { background: rgba(168, 85, 247, .15); }
.ds-rack-hotspot.locked {
  pointer-events: none;
  cursor: default;
}
.ds-rack-hotspot.locked .ds-rack-pulse { display: none; }
.ds-rack-hotspot.done { pointer-events: none; }
.ds-rack-hotspot.done .ds-rack-pulse { display: none; }
.ds-rack-pulse {
  position: absolute;
  inset: 0;
  border-radius: 8px;
  border: 3px solid rgba(168, 85, 247, .85);
  pointer-events: none;
  animation: dsRingPulse 1.6s ease-out infinite;
}
.ds-rack-pulse.delay { animation-delay: .8s; }

/* Hovering pipette */
.ds-pipette {
  position: absolute;
  left: 89.33%;
  top:  14.53%;
  width: 1.25%;
  height: 32%;
  z-index: 14;
  cursor: grab;
  transform-origin: 50% 100%;          /* pivot at the tip (bottom) for rotation */
  transition: left .6s ease-in-out, top .6s ease-in-out, transform .4s ease-out;
  animation: pipetteBob 1.6s ease-in-out infinite;
  transform: translate(-50%, -50%);
}
.ds-pipette img {
  position: absolute;
  inset: 0;
  display: block;
  width: 100%;
  height: 100%;
  pointer-events: none;
  -webkit-user-drag: none;
  user-select: none;
}
.ds-pipette-liquid {
  z-index: 1;
  /* Empty initially — clip everything from top to bottom */
  clip-path: inset(100% 0 0 0);
  transition: clip-path .7s ease-out;
  opacity: 0.5;
}
.ds-pipette-outline {
  z-index: 2;
}
.ds-pipette.filled .ds-pipette-liquid {
  clip-path: inset(0% 0 0 0);   /* fully visible */
}
.ds-pipette:active { cursor: grabbing; }
.ds-pipette.dragging { opacity: .35; }
@keyframes pipetteBob {
  0%, 100% { transform: translate(-50%, -50%); }
  50%      { transform: translate(-50%, calc(-50% - 6px)); }
}

.ds-pipette.over-chamber {
  animation: none;
  left: 32.5%;
  top:  44.67%;
  transform: translate(-50%, -100%) rotate(-55deg);
}

.ds-pipette.over-filter {
  animation: none;
  left: 46.17%;
  top:  58.93%;
  transform: translate(-50%, -100%) rotate(32deg);
}

/* Pipette drop zones (slightly larger than targets) */
.ds-pipette-drop-zone {
  position: absolute;
  transform: translate(-50%, -50%);
  border: 2.5px dashed transparent;
  border-radius: 6px;
  background: transparent;
  z-index: 12;
  display: none;
  pointer-events: auto;
  transition: all .2s;
}
.ds-pipette-drop-zone.show { display: block; }
.ds-pipette-drop-zone.over {
  border-color: #16a34a;
  background: rgba(34,197,94, .25);
  transform: translate(-50%, -50%) scale(1.05);
}
.ds-pipette-drop-chamber {
  left: 29.42%;
  top:  51.53%;
  width: 16%;
  height: 32%;
}
.ds-pipette-drop-filter {
  left: 46.08%;
  top:  64.8%;
  width: 8%;
  height: 14%;
}

/* Filter (empty stays visible always; filled overlay clip-paths up to 80%)
   Center (553, 486); PNG 60×80 */
.ds-filter {
  position: absolute;
  left: 46.08%;
  top:  64.8%;
  transform: translate(-50%, -50%);
  width: 5%;
  height: 10.67%;
  z-index: 5;
  pointer-events: none;
}
.ds-filter img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
}
.ds-filter-filled {
  clip-path: inset(100% 0 0 0);          /* hidden initially */
  transition: clip-path 1s ease-out;
  opacity: 0.5;                          /* 50% opacity for liquid */
}
.ds-filter-filled.filling {
  clip-path: inset(25% 0 0 0);           /* fills to 75% from bottom */
}

/* ══ STEP 9: SAMPLE CUVETTE + STEP 9 PIPETTE POSES + DROP ZONES */

/* Sample cuvette
   Center (730, 514); PNG 80×160 */
.ds-cuvette {
  position: absolute;
  transform: translate(-50%, -50%);
  z-index: 5;
  pointer-events: none;
}
/* Sample cuvette
   Bottom should land at (730, 530) — center y = 530 - 80 = 450 */
.ds-cuvette-sample {
  left: 60.83%;
  top:  60%;
  width: 6.67%;
  height: 21.33%;
  display: none;
}
.ds-cuvette-sample.show { display: block; }
.ds-cuvette img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
}
.ds-cuvette-filled {
  clip-path: inset(100% 0 0 0);          /* hidden initially */
  transition: clip-path 1s ease-out;
  opacity: 0.5;
}
.ds-cuvette-filled.filling {
  clip-path: inset(20% 0 0 0);           /* fills to 80% */
}

/* Pipette pose over filter again (Step 9 reuses the filter pose, same as Step 8) */
.ds-pipette.over-filter-step9 {
  animation: none;
  left: 46.17%;
  top:  58.93%;
  transform: translate(-50%, -100%) rotate(32deg);
}

/* Pipette pose over sample cuvette
   Tip at (750, 407) → 62.5%, 54.27%; same 32° clockwise tilt for consistency */
.ds-pipette.over-sample {
  animation: none;
  left: 62.5%;
  top:  54.27%;
  transform: translate(-50%, -100%) rotate(32deg);
}

/* Step 9 drop zones */
.ds-pipette-drop-filter-step9 {
  left: 46.08%;
  top:  64.8%;
  width: 8%;
  height: 14%;
}
.ds-pipette-drop-sample {
  left: 60.83%;
  top:  60%;
  width: 10%;
  height: 26%;
}

/* ══ STEP 10: BLANK CUVETTE + STEP 10 POSES + DROP ZONES ══════════════ */

/* Blank cuvette */
.ds-cuvette-blank {
  left: 53.08%;
  top:  60%;
  width: 6.67%;
  height: 21.33%;
  display: none;
}
.ds-cuvette-blank.show { display: block; }

/* Pipette pose over volumetric flask */
.ds-pipette.over-volumetric {
  animation: none;
  left: 68.42%;
  top:  46.53%;
  transform: translate(-50%, -100%) rotate(13deg);
}

/* Pipette pose over blank cuvette */
.ds-pipette.over-blank {
  animation: none;
  left: 54.17%;
  top:  54.27%;
  transform: translate(-50%, -100%) rotate(32deg);
}

/* Step 10 drop zones */
.ds-pipette-drop-volumetric {
  left: 67.25%;
  top:  49.87%;
  width: 12%;
  height: 50%;
}
.ds-pipette-drop-blank {
  left: 53.08%;
  top:  60%;
  width: 10%;
  height: 26%;
}

</style>


<!-- ══════════════════════════════════════════════════════════════════════════
     MAIN DISSOLUTION MODAL
     ══════════════════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalDissolution">
  <div class="lab-modal" style="max-width:1000px;">
    <div class="lab-modal-header" style="padding:14px 22px 12px;">
      <h2>
        <div class="modal-icon tool-icon-dissolution">
          <svg width="26" height="26" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Round flask body -->
            <circle cx="16" cy="22" r="7" fill="#3b82f6" opacity=".55"/>
            <circle cx="16" cy="22" r="7" fill="none" stroke="#1a6bb5" stroke-width="1.6"/>
            <!-- Flask neck -->
            <rect x="14" y="6" width="4" height="9" fill="#ffffff" stroke="#1a6bb5" stroke-width="1.6"/>
            <!-- Top rim -->
            <line x1="12.5" y1="6" x2="19.5" y2="6" stroke="#1a6bb5" stroke-width="1.8" stroke-linecap="round"/>
            <!-- Stirring paddle shaft going down through neck -->
            <line x1="16" y1="6.5" x2="16" y2="22" stroke="#0d2d4e" stroke-width="1.2"/>
            <!-- Paddle blade at bottom -->
            <ellipse cx="16" cy="22.5" rx="3.5" ry="1" fill="#d1d5db" stroke="#0d2d4e" stroke-width="1.2"/>
            <!-- Small bubble for visual interest -->
            <circle cx="13" cy="19" r=".8" fill="#fff" opacity=".7"/>
            <circle cx="19" cy="24" r=".6" fill="#fff" opacity=".6"/>
          </svg>
        </div>
        Dissolution Apparatus
      </h2>
      <div class="modal-header-actions">
        <button class="ds-restart-btn" onclick="dsRestartTool()" title="Restart this tool from the beginning">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12a9 9 0 1 0 3-6.7"/><polyline points="3 4 3 10 9 10"/></svg>
          Restart Tool
        </button>
        <button class="modal-close-btn" onclick="closeModal('modalDissolution')">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>
    </div>
    <div class="lab-modal-body" style="padding:10px 22px 22px;">

      <!-- Step progress -->
      <div class="uvvis-steps">
        <div class="uvvis-step active" id="dsStep1">
          <div class="uvvis-step-num">1</div>
          <span>Transfer Medium</span>
        </div>
        <div class="uvvis-step" id="dsStep2">
          <div class="uvvis-step-num">2</div>
          <span>Fill Chamber</span>
        </div>
        <div class="uvvis-step" id="dsStep3">
          <div class="uvvis-step-num">3</div>
          <span>Open Container</span>
        </div>
        <div class="uvvis-step" id="dsStep4">
          <div class="uvvis-step-num">4</div>
          <span>Place Tablet</span>
        </div>
        <div class="uvvis-step" id="dsStep5">
          <div class="uvvis-step-num">5</div>
          <span>Set RPM</span>
        </div>
        <div class="uvvis-step" id="dsStep6">
          <div class="uvvis-step-num">6</div>
          <span>Set Time</span>
        </div>
        <div class="uvvis-step" id="dsStep7">
          <div class="uvvis-step-num">7</div>
          <span>Run</span>
        </div>
        <div class="uvvis-step" id="dsStep8">
          <div class="uvvis-step-num">8</div>
          <span>Extract Sample</span>
        </div>
        <div class="uvvis-step" id="dsStep9">
          <div class="uvvis-step-num">9</div>
          <span>Transfer to Cuvette</span>
        </div>
        <div class="uvvis-step" id="dsStep10">
          <div class="uvvis-step-num">10</div>
          <span>Prepare Blank</span>
        </div>
      </div>

      <!-- Instruction banner -->
      <div class="instruction-banner">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span id="dsInstructionText">Drag the <strong>round flask</strong> onto the <strong>volumetric flask</strong> to begin pouring.</span>
      </div>

      <!-- ═══ LAYERED SCENE ═══ -->
      <div class="ds-scene" id="dsScene">

        <img src="/qcsim/Assets/VirtualLab/dissolution_bg.png"
             class="ds-scene-bg" id="dsSceneBg" alt="">

        <!-- Chamber filled PNG (hidden until Step 2 pour completes) -->
        <div class="ds-obj ds-chamber-filled" id="dsChamberFilled">
          <img src="/qcsim/Assets/VirtualLab/chamber_filled.png" alt="">
        </div>

        <!-- Chamber drop zone (Step 2 only) -->
        <div class="ds-drop-zone ds-chamber-drop-zone"
             id="dsChamberDropZone"
             ondragover="dsDragOver(event)"
             ondragleave="dsDragLeave(event)"
             ondrop="dsDropOnChamber(event)"></div>

        <!-- Volumetric flask (target) -->
        <div class="ds-obj ds-vol" id="dsVolFlask">
          <svg class="ds-vol-liquid-svg" viewBox="0 0 100 320" preserveAspectRatio="none">
            <defs>
              <linearGradient id="dsLiquidGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%"   stop-color="#60a5fa"/>
                <stop offset="100%" stop-color="#3b82f6"/>
              </linearGradient>
            </defs>
            <rect id="dsVolLiquidRect" x="18" y="304" width="64" height="0"
                  fill="url(#dsLiquidGradient)"/>
          </svg>
          <img class="ds-vol-img" src="/qcsim/Assets/VirtualLab/volumetric_flask.png" alt="">
          <div class="ds-vol-bands">
            <div class="ds-vol-band ds-vol-band-75"></div>
            <div class="ds-vol-band ds-vol-band-70"></div>
            <span class="ds-vol-band-label l75">75%</span>
            <span class="ds-vol-band-label l70">70%</span>
          </div>
        </div>

        <!-- Drop zone over volumetric -->
        <div class="ds-drop-zone"
             id="dsVolumetricDropZone"
             ondragover="dsDragOver(event)"
             ondragleave="dsDragLeave(event)"
             ondrop="dsDropOnVolumetric(event)"></div>

        <!-- Round flask (source, draggable) -->
        <div class="ds-obj ds-round"
             id="dsRoundFlask"
             draggable="true"
             ondragstart="dsRoundDragStart(event)"
             ondragend="dsRoundDragEnd(event)"
             title="Round Flask">
          <img class="ds-round-liquid"  id="dsRoundLiquidImg"
               src="/qcsim/Assets/VirtualLab/round_flask_liquid.png"  alt="">
          <img class="ds-round-outline" id="dsRoundOutlineImg"
               src="/qcsim/Assets/VirtualLab/round_flask_outline.png" alt="">
          <div class="ds-round-pulse"></div>
          <div class="ds-round-pulse delay"></div>
        </div>

        <!-- Tablet container — closed (clickable in Step 3) -->
        <div class="ds-obj ds-tablet" id="dsTabletContainer"
             onclick="dsOpenContainer()"
             title="Tablet container">
          <img src="/qcsim/Assets/VirtualLab/tablet_container.png" alt="Tablet container"
               id="dsTabletContainerClosed">
          <img src="/qcsim/Assets/VirtualLab/tablet_container_open.png" alt="Tablet container (open)"
               id="dsTabletContainerOpen" style="display:none;">
          <div class="ds-tablet-pulse"></div>
          <div class="ds-tablet-pulse delay"></div>
        </div>

        <!-- Draggable tablet (hidden until container is opened) -->
        <div class="ds-obj ds-tablet-draggable" id="dsTabletDraggable"
             draggable="true"
             ondragstart="dsTabletDragStart(event)"
             ondragend="dsTabletDragEnd(event)"
             title="Drag tablet to chamber"
             style="display:none;">
          <img src="/qcsim/Assets/VirtualLab/tablet.png" alt="Tablet">
        </div>

        <!-- Falling tablet (hidden until placed in chamber) -->
        <div class="ds-obj ds-tablet-falling" id="dsTabletFalling" style="display:none;">
          <img src="/qcsim/Assets/VirtualLab/tablet.png" alt="">
        </div>

        <!-- Tablet drop zone over the chamber (Step 4 only) -->
        <div class="ds-drop-zone ds-tablet-drop-zone"
             id="dsTabletDropZone"
             ondragover="dsTabletDragOver(event)"
             ondragleave="dsTabletDragLeave(event)"
             ondrop="dsTabletDrop(event)"></div>

        <!-- ════════ HOTSPOT: NUMPAD CALCULATOR (Step 5) ════════ -->
        <div class="ds-rpm-hotspot ds-rpm-hotspot-numpad locked"
             id="dsRpmNumpadHotspot"
             onclick="dsOpenRpmModal()"
             title="Set RPM">
          <div class="ds-rpm-pulse"></div>
          <div class="ds-rpm-pulse delay"></div>
        </div>

        <!-- ════════ HOTSPOT: SCREEN (Step 5, alternative) ════════ -->
        <div class="ds-rpm-hotspot ds-rpm-hotspot-screen locked"
             id="dsRpmScreenHotspot"
             onclick="dsOpenRpmModal()"
             title="Set RPM">
        </div>

        <!-- RPM DISPLAY on the screen (visible after confirm) -->
        <div class="ds-rpm-display" id="dsRpmDisplay">
          <span id="dsRpmDisplayValue">000</span>
        </div>

        <!-- ════════ HOTSPOT: TIME KNOB (Step 6) ════════ -->
        <div class="ds-time-hotspot locked"
             id="dsTimeKnobHotspot"
             onclick="dsOpenTimeModal()"
             title="Set Time">
          <div class="ds-time-pulse"></div>
          <div class="ds-time-pulse delay"></div>
        </div>

        <!-- TIME DISPLAY on the apparatus screen (visible after confirm) -->
        <div class="ds-time-display" id="dsTimeDisplay">
          <span id="dsTimeDisplayValue">00</span>
          <span class="ds-time-unit">s</span>
        </div>

        <!-- ════════ PADDLE (rotating, Step 7) ════════ -->
        <div class="ds-paddle" id="dsPaddle">
          <img src="/qcsim/Assets/VirtualLab/paddle.png" alt="Paddle" draggable="false">
        </div>

        <!-- ════════ HOTSPOT: START BUTTON (Step 7) ════════ -->
        <div class="ds-start-hotspot locked"
             id="dsStartHotspot"
             onclick="dsPressStart()"
             title="Start Machine">
          <div class="ds-start-pulse"></div>
          <div class="ds-start-pulse delay"></div>
        </div>

        <!-- ════════ STEP 8: PIPETTE RACK + PIPETTE + FILTER ════════ -->
        <div class="ds-rack-hotspot locked"
             id="dsRackHotspot"
             onclick="dsClickRack()"
             title="Pipette Rack">
          <div class="ds-rack-pulse"></div>
          <div class="ds-rack-pulse delay"></div>
        </div>

        <!-- Hovering pipette (appears after rack click) -->
        <div class="ds-pipette" id="dsPipette" style="display:none;"
             draggable="true"
             ondragstart="dsPipetteDragStart(event)"
             ondragend="dsPipetteDragEnd(event)">
          <img class="ds-pipette-liquid"  id="dsPipetteLiquid"
               src="/qcsim/Assets/VirtualLab/pipette_liquid.png"  alt="" draggable="false">
          <img class="ds-pipette-outline" src="/qcsim/Assets/VirtualLab/pipette_outline.png" alt="Pipette" draggable="false">
        </div>

        <!-- Pipette drop zone over chamber (Step 8a) -->
        <div class="ds-pipette-drop-zone ds-pipette-drop-chamber"
             id="dsPipetteDropChamber"
             ondragover="dsPipetteDragOver(event)"
             ondragleave="dsPipetteDragLeave(event)"
             ondrop="dsPipetteDropOnChamber(event)"></div>

        <!-- Pipette drop zone over filter (Step 8b) -->
        <div class="ds-pipette-drop-zone ds-pipette-drop-filter"
             id="dsPipetteDropFilter"
             ondragover="dsPipetteDragOver(event)"
             ondragleave="dsPipetteDragLeave(event)"
             ondrop="dsPipetteDropOnFilter(event)"></div>

        <!-- Filter (empty + filled overlay) -->
        <div class="ds-filter">
          <img class="ds-filter-empty"  id="dsFilterEmpty"
               src="/qcsim/Assets/VirtualLab/filter_empty.png"  alt="Filter">
          <img class="ds-filter-empty"  id="dsBeakerOnly"
               src="/qcsim/Assets/VirtualLab/beaker_only.png" alt=""
               style="display:none;">
          <!-- Filled state with funnel still on (initial pour animation) -->
          <img class="ds-filter-filled" id="dsFilterFilled"
               src="/qcsim/Assets/VirtualLab/filter_filled.png" alt="">
          <!-- Water-only state (after funnel removed) -->
          <img class="ds-filter-filled" id="dsFilterWaterOnly"
               src="/qcsim/Assets/VirtualLab/filter_water_only.png" alt=""
               style="display:none;">
        </div>

        <!-- ════════ STEP 9: SAMPLE CUVETTE ════════ -->
        <div class="ds-cuvette ds-cuvette-sample" id="dsSampleCuvette">
          <img class="ds-cuvette-empty" src="/qcsim/Assets/VirtualLab/sample_cuvette_empty.png" alt="Sample Cuvette">
          <img class="ds-cuvette-filled" id="dsSampleCuvetteFilled"
               src="/qcsim/Assets/VirtualLab/sample_cuvette_filled.png" alt="">
        </div>

        <!-- ════════ STEP 10: BLANK CUVETTE ════════ -->
        <div class="ds-cuvette ds-cuvette-blank" id="dsBlankCuvette">
          <img class="ds-cuvette-empty" src="/qcsim/Assets/VirtualLab/sample_cuvette_empty.png" alt="Blank Cuvette">
          <img class="ds-cuvette-filled" id="dsBlankCuvetteFilled"
               src="/qcsim/Assets/VirtualLab/sample_cuvette_filled.png" alt="">
        </div>

        <!-- Pipette drop zone over filter (Step 9a) -->
        <div class="ds-pipette-drop-zone ds-pipette-drop-filter-step9"
             id="dsPipetteDropFilterStep9"
             ondragover="dsPipetteDragOver(event)"
             ondragleave="dsPipetteDragLeave(event)"
             ondrop="dsPipetteDropOnFilterStep9(event)"></div>

        <!-- Pipette drop zone over sample cuvette (Step 9b) -->
        <div class="ds-pipette-drop-zone ds-pipette-drop-sample"
             id="dsPipetteDropSample"
             ondragover="dsPipetteDragOver(event)"
             ondragleave="dsPipetteDragLeave(event)"
             ondrop="dsPipetteDropOnSample(event)"></div>

        <!-- Pipette drop zone over volumetric flask (Step 10a) -->
        <div class="ds-pipette-drop-zone ds-pipette-drop-volumetric"
             id="dsPipetteDropVolumetric"
             ondragover="dsPipetteDragOver(event)"
             ondragleave="dsPipetteDragLeave(event)"
             ondrop="dsPipetteDropOnVolumetric(event)"></div>

        <!-- Pipette drop zone over blank cuvette (Step 10b) -->
        <div class="ds-pipette-drop-zone ds-pipette-drop-blank"
             id="dsPipetteDropBlank"
             ondragover="dsPipetteDragOver(event)"
             ondragleave="dsPipetteDragLeave(event)"
             ondrop="dsPipetteDropOnBlank(event)"></div>

      </div>
      <!-- ═══ /LAYERED SCENE ═══ -->

      <!-- Inline pour controls (revealed after first drop) -->
      <div class="ds-inline-controls" id="dsInlineControls">
        <div class="ds-inline-controls-header">
          <span>Volumetric Fill:</span>
          <span class="ds-level-pill" id="dsLevelPill">0.0%</span>
        </div>
        <div class="ds-controls">
          <button class="btn btn-primary ds-pour-btn"
                  id="dsPourBtn"
                  onmousedown="dsStartPour()" onmouseup="dsStopPour()" onmouseleave="dsStopPour()"
                  ontouchstart="event.preventDefault();dsStartPour()" ontouchend="dsStopPour()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18l-2 13H5L3 6z"/></svg>
            Hold to Pour
          </button>
          <button class="btn btn-outline" onclick="dsConfirmStop()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="6" y="6" width="12" height="12" rx="1"/></svg>
            Stop
          </button>
        </div>
      </div>

    </div>
  </div>
</div>


<!-- ══════════════════ TIME INPUT MODAL (Step 6) ══════════════════ -->
<div class="modal-overlay" id="modalDsTime">
  <div class="lab-modal" style="max-width:380px;">
    <div class="lab-modal-header">
      <h2>
        <div class="modal-icon" style="background:#a855f7;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        Set Time
      </h2>
      <button class="modal-close-btn" onclick="dsCloseTimeModal()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>
    <div class="lab-modal-body" style="padding:18px 20px 22px;">

      <p class="wl-hint">Turn the knob to set time between <strong>5 – 30 seconds</strong>.</p>

      <!-- Knob with ◀ ▶ controls -->
      <div class="ds-knob-row">
        <button class="ds-knob-arrow"
                onmousedown="dsStartTimeArrow(-1)" onmouseup="dsStopTimeArrow()" onmouseleave="dsStopTimeArrow()"
                ontouchstart="event.preventDefault();dsStartTimeArrow(-1)" ontouchend="dsStopTimeArrow()"
                aria-label="Decrease">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <div class="ds-knob" id="dsKnob"></div>
        <button class="ds-knob-arrow"
                onmousedown="dsStartTimeArrow(1)" onmouseup="dsStopTimeArrow()" onmouseleave="dsStopTimeArrow()"
                ontouchstart="event.preventDefault();dsStartTimeArrow(1)" ontouchend="dsStopTimeArrow()"
                aria-label="Increase">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>

      <!-- Readout -->
      <div class="ds-time-readout" id="dsTimeReadout">
        <div class="ds-time-readout-label">Run Time</div>
        <div class="ds-time-readout-value">
          <span id="dsTimeReadoutValue">5</span><span class="unit">s</span>
        </div>
      </div>

      <!-- Error -->
      <div class="wl-error hidden" id="dsTimeError">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span id="dsTimeErrorText">Time must be at least 5 seconds.</span>
      </div>

      <!-- Confirm -->
      <button class="btn btn-primary btn-full wl-confirm" id="dsTimeConfirmBtn" onclick="dsTimeConfirm()">
        Confirm Time
      </button>

    </div>
  </div>
</div>


<!-- ══════════════════ RPM INPUT MODAL (Step 5) ══════════════════ -->
<div class="modal-overlay" id="modalDsRpm">
  <div class="lab-modal" style="max-width:380px;">
    <div class="lab-modal-header">
      <h2>
        <div class="modal-icon" style="background:#1a6bb5;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
        Set RPM
      </h2>
      <button class="modal-close-btn" onclick="dsCloseRpmModal()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>
    <div class="lab-modal-body" style="padding:18px 20px 22px;">

      <p class="wl-hint">Enter an RPM between <strong>50 – 75</strong>.</p>

      <!-- Display -->
      <div class="wl-display" id="dsRpmInputDisplay">
        <div class="wl-value" id="dsRpmInputValue">0</div>
        <div class="wl-unit">rpm</div>
      </div>

      <!-- Numpad -->
      <div class="wl-numpad">
        <button class="wl-key" onclick="dsRpmKey('1')">1</button>
        <button class="wl-key" onclick="dsRpmKey('2')">2</button>
        <button class="wl-key" onclick="dsRpmKey('3')">3</button>
        <button class="wl-key" onclick="dsRpmKey('4')">4</button>
        <button class="wl-key" onclick="dsRpmKey('5')">5</button>
        <button class="wl-key" onclick="dsRpmKey('6')">6</button>
        <button class="wl-key" onclick="dsRpmKey('7')">7</button>
        <button class="wl-key" onclick="dsRpmKey('8')">8</button>
        <button class="wl-key" onclick="dsRpmKey('9')">9</button>
        <button class="wl-key wl-key-clear" onclick="dsRpmClear()">C</button>
        <button class="wl-key" onclick="dsRpmKey('0')">0</button>
        <button class="wl-key wl-key-back" onclick="dsRpmBack()">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"/><line x1="18" y1="9" x2="12" y2="15"/><line x1="12" y1="9" x2="18" y2="15"/></svg>
        </button>
      </div>

      <!-- Error -->
      <div class="wl-error hidden" id="dsRpmError">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span id="dsRpmErrorText">RPM invalid. Please input a number between 50 – 75.</span>
      </div>

      <!-- Confirm -->
      <button class="btn btn-primary btn-full wl-confirm" id="dsRpmConfirmBtn" onclick="dsRpmConfirm()">
        Confirm RPM
      </button>

    </div>
  </div>
</div>


<!-- ── OVERFLOW ERROR MODAL ─────────────────────────────────────────────── -->
<div class="modal-overlay" id="modalDsOverflow">
  <div class="lab-modal" style="max-width:380px;">
    <div class="locked-modal-body" style="padding:36px 28px 28px;">
      <div class="lock-icon" style="background:#fff5f5;">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2"><path d="M12 9v4M12 17h.01"/><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
      </div>
      <h3 style="color:#dc2626;">Threshold Exceeded!</h3>
      <p>You poured too much medium. The flasks will be reset so you can try again.</p>
      <button class="btn btn-primary" style="margin-top:18px;" onclick="dsResetFlasks()">OK, Try Again</button>
    </div>
  </div>
</div>


<script>
/* ──────────────────────────────────────────────────────────────────────────
   DISSOLUTION APPARATUS — STEP 1: TRANSFER MEDIUM
   ──────────────────────────────────────────────────────────────────────── */

const DS_CONFIG = {
  targetMin:    70,
  targetMax:    75,
  pourSpeed:    0.85,    // % per ~17ms tick — volumetric fill rate
  drainRatio:   0.667,   // round flask drains at this fraction of fill rate
                         //   so when vol reaches 75%, round = 100 - (75 * 0.667) = 50%
  overflowAt:   76,
  chamberFillSpeed: 1.2, // % per tick when pouring into chamber (Step 2)
};

// Volumetric flask SVG geometry (viewBox 100×320)
const DS_VOL = {
  yBottom:     304,
  innerHeight: 280,
};

let dsRoundLevel    = 100;
let dsVolLevel      = 0;
let dsIsPouring     = false;
let dsPourTimer     = null;
let dsTransferDone  = false;
let dsArmed         = false;   // true once round flask has been dragged onto volumetric

/* ── DRAG & DROP ──────────────────────────────────────────────────────── */
function dsRoundDragStart(e) {
  // Block drag only after entire chamber pour is done
  if (dsChamberDone) { e.preventDefault(); return; }
  e.dataTransfer.setData('text/plain', 'round-flask');
  e.dataTransfer.effectAllowed = 'move';
  e.target.classList.add('dragging');
}
function dsRoundDragEnd(e) {
  e.target.classList.remove('dragging');
}
function dsDragOver(e) {
  e.preventDefault();
  e.dataTransfer.dropEffect = 'move';
  e.currentTarget.classList.add('over');
}
function dsDragLeave(e) {
  e.currentTarget.classList.remove('over');
}
function dsDropOnVolumetric(e) {
  e.preventDefault();
  e.currentTarget.classList.remove('over');
  if (dsTransferDone) return;
  const type = e.dataTransfer.getData('text/plain');
  if (type !== 'round-flask') return;

  // First successful drop arms the pour controls inline (no popup)
  dsArmed = true;
  document.getElementById('dsRoundFlask').classList.add('armed');
  document.getElementById('dsInlineControls').classList.add('show');
  document.getElementById('dsInstructionText').innerHTML =
    'Hold <strong>Pour</strong> to transfer medium until it is between the <strong style="color:#dc2626">two red bands</strong>.';
  dsRender();
}

/* ── POUR CONTROLS ────────────────────────────────────────────────────── */
function dsStartPour() {
  if (!dsArmed || dsIsPouring || dsTransferDone) return;
  if (dsRoundLevel <= 0) return;
  if (dsVolLevel >= DS_CONFIG.overflowAt) return;

  dsIsPouring = true;
  document.getElementById('dsPourBtn').classList.add('pouring');
  document.getElementById('dsRoundFlask').classList.add('tilted');
  window.labAudio?.startPour();

  dsPourTimer = setInterval(() => {
    const fillStep  = DS_CONFIG.pourSpeed;
    const drainStep = fillStep * DS_CONFIG.drainRatio;
    dsRoundLevel = Math.max(0, dsRoundLevel - drainStep);
    dsVolLevel  += fillStep;

    dsRender();

    if (dsVolLevel >= DS_CONFIG.overflowAt) {
      dsStopPour();
      setTimeout(() => {
        document.getElementById('modalDsOverflow').classList.add('open');
      }, 250);
      return;
    }
    if (dsRoundLevel <= 0) dsStopPour();
  }, 17);
}

function dsStopPour() {
  if (!dsIsPouring) return;
  clearInterval(dsPourTimer);
  dsPourTimer = null;
  dsIsPouring = false;
  document.getElementById('dsPourBtn').classList.remove('pouring');
  document.getElementById('dsRoundFlask').classList.remove('tilted');
  window.labAudio?.stopPour();
}

/* User pressed Stop button — check completion */
function dsConfirmStop() {
  dsStopPour();
  if (dsTransferDone) return;

  if (dsVolLevel >= DS_CONFIG.targetMin && dsVolLevel <= DS_CONFIG.targetMax) {
    // Step 1 success — advance to Step 2 (drag to chamber)
    dsTransferDone = true;
    document.getElementById('dsRoundFlask').classList.remove('armed');
    document.getElementById('dsStep1').classList.remove('active');
    document.getElementById('dsStep1').classList.add('done');
    document.getElementById('dsStep2')?.classList.add('active');
    document.getElementById('dsInstructionText').innerHTML =
      `✅ Volumetric filled to ${dsVolLevel.toFixed(1)}%. Now drag the <strong>round flask</strong> onto the <strong>dissolution chamber</strong> to transfer the rest.`;
    document.getElementById('dsInlineControls').classList.remove('show');
    document.getElementById('dsVolumetricDropZone').style.display = 'none';
    document.getElementById('dsChamberDropZone').classList.add('show');
  } else if (dsVolLevel < DS_CONFIG.targetMin) {
    document.getElementById('dsInstructionText').innerHTML =
      `⚠️ Only ${dsVolLevel.toFixed(1)}% filled — keep pouring until you reach the red band.`;
  } else {
    document.getElementById('dsInstructionText').innerHTML =
      `⚠️ Slightly above the red band (${dsVolLevel.toFixed(1)}%). Keep within 70–75%.`;
  }
}

/* ──────────────────────────────────────────────────────────────────────────
   STEP 2: TRANSFER REMAINING MEDIUM TO DISSOLUTION CHAMBER
   ──────────────────────────────────────────────────────────────────────── */

let dsChamberDone = false;

function dsDropOnChamber(e) {
  e.preventDefault();
  e.currentTarget.classList.remove('over');
  if (dsChamberDone) return;
  const type = e.dataTransfer.getData('text/plain');
  if (type !== 'round-flask') return;

  // Auto-pour: tilt flask toward chamber, drain the rest, fill chamber
  dsChamberDone = true;
  const flask     = document.getElementById('dsRoundFlask');
  const chamberFx = document.getElementById('dsChamberFilled');
  const dropZone  = document.getElementById('dsChamberDropZone');

  flask.setAttribute('draggable', 'false');
  dropZone.classList.remove('show');

  // Apply chamber pour tilt (different from volumetric pour pose)
  flask.classList.add('tilted-chamber');

  document.getElementById('dsInstructionText').innerHTML =
    'Pouring remaining medium into the dissolution chamber…';

  // After tilt animation finishes, start draining + filling
  setTimeout(() => {
    chamberFx.classList.add('filling');           // CSS animates clip-path over 1.4s
    window.labAudio?.startPour();

    // Drain remaining round flask in sync with chamber fill (~1.4s)
    const startLevel = dsRoundLevel;
    const startTime  = performance.now();
    const duration   = 1400;

    function drainStep(now) {
      const t = Math.min(1, (now - startTime) / duration);
      dsRoundLevel = startLevel * (1 - t);
      dsRender();
      if (t < 1) {
        requestAnimationFrame(drainStep);
      } else {
        window.labAudio?.stopPour();
        // Done draining — return flask to rest after a beat, advance to Step 3
        setTimeout(() => {
          flask.classList.remove('tilted-chamber');
          flask.classList.add('done');
          // Round flask is empty — fade and hide it
          flask.style.transition = 'opacity .4s ease-out';
          flask.style.opacity    = '0';
          setTimeout(() => {
            flask.style.display = 'none';
            // Reveal both cuvettes now that the round flask is gone
            document.getElementById('dsSampleCuvette')?.classList.add('show');
            document.getElementById('dsBlankCuvette')?.classList.add('show');
          }, 450);
          document.getElementById('dsStep2')?.classList.remove('active');
          document.getElementById('dsStep2')?.classList.add('done');
          document.getElementById('dsStep3')?.classList.add('active');
          // Arm the tablet container — it now glows and is clickable
          document.getElementById('dsTabletContainer')?.classList.add('armed');
          document.getElementById('dsInstructionText').innerHTML =
            '✅ Chamber filled. Now click the <strong>tablet container</strong> to open it.';
        }, 400);
      }
    }
    requestAnimationFrame(drainStep);
  }, 450); // wait for tilt-chamber transition (.4s)
}

/* ── RENDER ───────────────────────────────────────────────────────────── */
function dsRender() {
  // Round flask: drain by clip-path inset from top
  const topInset  = 100 - dsRoundLevel;
  const liquidImg = document.getElementById('dsRoundLiquidImg');
  if (liquidImg) liquidImg.style.clipPath = `inset(${topInset}% 0 0 0)`;

  // Volumetric flask: SVG rect grows upward
  const liqHeight = DS_VOL.innerHeight * (dsVolLevel / 100);
  const liqY      = DS_VOL.yBottom - liqHeight;
  const rect      = document.getElementById('dsVolLiquidRect');
  if (rect) {
    rect.setAttribute('y',      liqY);
    rect.setAttribute('height', liqHeight);
  }

  // Level pill
  const pill = document.getElementById('dsLevelPill');
  if (pill) {
    pill.textContent = dsVolLevel.toFixed(1) + '%';
    pill.classList.remove('in-range', 'over');
    if      (dsVolLevel > DS_CONFIG.targetMax)  pill.classList.add('over');
    else if (dsVolLevel >= DS_CONFIG.targetMin) pill.classList.add('in-range');
  }
}

/* ── RESET (after overflow) ───────────────────────────────────────────── */
function dsResetFlasks() {
  dsRoundLevel = 100;
  dsVolLevel   = 0;
  dsIsPouring  = false;
  if (dsPourTimer) { clearInterval(dsPourTimer); dsPourTimer = null; }
  document.getElementById('modalDsOverflow').classList.remove('open');
  document.getElementById('dsInstructionText').innerHTML =
    'Hold <strong>Pour</strong> to transfer medium until it is between the <strong style="color:#dc2626">two red bands</strong>.';
  dsRender();
}

/* ──────────────────────────────────────────────────────────────────────────
   STEP 3: OPEN TABLET CONTAINER
   ──────────────────────────────────────────────────────────────────────── */

let dsContainerOpen = false;

function dsOpenContainer() {
  if (dsContainerOpen) return;
  const container = document.getElementById('dsTabletContainer');
  if (!container.classList.contains('armed')) return; // not yet unlocked

  dsContainerOpen = true;
  container.classList.remove('armed');
  container.classList.add('opened');

  // Swap closed PNG → open PNG
  document.getElementById('dsTabletContainerClosed').style.display = 'none';
  document.getElementById('dsTabletContainerOpen').style.display   = 'block';

  // Reveal the draggable tablet + drop zone
  document.getElementById('dsTabletDraggable').style.display = 'block';
  document.getElementById('dsTabletDropZone').classList.add('show');

  // Advance progress
  document.getElementById('dsStep3')?.classList.remove('active');
  document.getElementById('dsStep3')?.classList.add('done');
  document.getElementById('dsStep4')?.classList.add('active');

  document.getElementById('dsInstructionText').innerHTML =
    '🧪 Container opened. Now <strong>drag the tablet</strong> into the dissolution chamber.';
}

/* ──────────────────────────────────────────────────────────────────────────
   STEP 4: DRAG TABLET INTO CHAMBER (it descends slowly)
   ──────────────────────────────────────────────────────────────────────── */

let dsTabletPlaced = false;

function dsTabletDragStart(e) {
  if (dsTabletPlaced) { e.preventDefault(); return; }
  e.dataTransfer.setData('text/plain', 'tablet');
  e.dataTransfer.effectAllowed = 'move';
  e.target.classList.add('dragging');
}
function dsTabletDragEnd(e) {
  e.target.classList.remove('dragging');
}
function dsTabletDragOver(e) {
  e.preventDefault();
  e.dataTransfer.dropEffect = 'move';
  e.currentTarget.classList.add('over');
}
function dsTabletDragLeave(e) {
  e.currentTarget.classList.remove('over');
}
function dsTabletDrop(e) {
  e.preventDefault();
  e.currentTarget.classList.remove('over');
  if (dsTabletPlaced) return;
  const type = e.dataTransfer.getData('text/plain');
  if (type !== 'tablet') return;

  dsTabletPlaced = true;

  // Hide draggable tablet + drop zone
  document.getElementById('dsTabletDraggable').style.display = 'none';
  document.getElementById('dsTabletDropZone').classList.remove('show');

  // Show falling tablet at top of chamber, then animate descent
  const falling = document.getElementById('dsTabletFalling');
  falling.style.display = 'block';

  // Initial position: above the chamber opening
  // Chamber filled center is (353, 386.5), top is around y = 386.5 - 87 = 299
  // Start the tablet ABOVE the chamber opening
  const startX = 29.42;       // % matches chamber center
  const startY = 38;          // % — just above chamber top
  const endX   = 29.0;        // % matches target (348/1200)
  const endY   = 60.93;       // % matches target (457/750)

  falling.style.left = startX + '%';
  falling.style.top  = startY + '%';
  falling.style.transform = 'translate(-50%, -50%)';
  falling.style.transition = 'top 2.4s ease-in, left 2.4s linear';

  // Animate to bottom of chamber
  requestAnimationFrame(() => {
    falling.style.left = endX + '%';
    falling.style.top  = endY + '%';
  });

  document.getElementById('dsInstructionText').innerHTML =
    'Tablet descending into the chamber…';

  // After landing, advance progress
  setTimeout(() => {
    document.getElementById('dsStep4')?.classList.remove('active');
    document.getElementById('dsStep4')?.classList.add('done');
    document.getElementById('dsStep5')?.classList.add('active');
    // Unlock the two RPM hotspots
    document.getElementById('dsRpmNumpadHotspot')?.classList.remove('locked');
    document.getElementById('dsRpmScreenHotspot')?.classList.remove('locked');
    document.getElementById('dsInstructionText').innerHTML =
      '✅ Tablet placed inside the chamber. Now click the <strong>numpad</strong> or <strong>screen</strong> on the apparatus to set the RPM.';
  }, 2500);
}

/* ──────────────────────────────────────────────────────────────────────────
   STEP 5: SET RPM VIA NUMPAD POPUP
   ──────────────────────────────────────────────────────────────────────── */

const DS_RPM_CONFIG = {
  min: 50,
  max: 75,
};

let dsRpmInput   = '';        // raw string typed in modal
let dsRpmCurrent = 0;         // current numeric value
let dsRpmSet     = false;     // true after confirmed

function dsOpenRpmModal() {
  if (dsRpmSet) return;
  // Reset transient state
  dsRpmInput = '';
  dsRpmCurrent = 0;
  dsUpdateRpmDisplay();
  dsHideRpmError();
  document.getElementById('modalDsRpm').classList.add('open');
}

function dsCloseRpmModal() {
  document.getElementById('modalDsRpm').classList.remove('open');
}

function dsUpdateRpmDisplay() {
  const v = (dsRpmInput === '') ? String(dsRpmCurrent) : dsRpmInput;
  document.getElementById('dsRpmInputValue').textContent = v;
}

function dsRpmKey(d) {
  if (dsRpmSet) return;
  dsHideRpmError();
  // Limit to 2 digits (max 75)
  if (dsRpmInput.length >= 2) return;
  if (dsRpmInput === '0') dsRpmInput = '';
  dsRpmInput += d;
  dsRpmCurrent = parseInt(dsRpmInput, 10) || 0;
  dsUpdateRpmDisplay();
  playClickTone(900, 600);
}

function dsRpmBack() {
  if (dsRpmSet) return;
  dsHideRpmError();
  if (dsRpmInput.length > 0) {
    dsRpmInput = dsRpmInput.slice(0, -1);
    dsRpmCurrent = parseInt(dsRpmInput, 10) || 0;
    dsUpdateRpmDisplay();
    playClickTone(500, 300);
  }
}

function dsRpmClear() {
  if (dsRpmSet) return;
  dsRpmInput = '';
  dsRpmCurrent = 0;
  dsHideRpmError();
  dsUpdateRpmDisplay();
  playClickTone(500, 300);
}

function dsShowRpmError(msg) {
  const err = document.getElementById('dsRpmError');
  document.getElementById('dsRpmErrorText').textContent = msg;
  err.classList.remove('hidden');
  const display = document.getElementById('dsRpmInputDisplay');
  display.classList.add('shake');
  setTimeout(() => display.classList.remove('shake'), 500);
}
function dsHideRpmError() {
  document.getElementById('dsRpmError').classList.add('hidden');
}

function dsRpmConfirm() {
  if (dsRpmSet) return;
  const v = dsRpmCurrent;
  if (dsRpmInput === '' && v === 0) {
    dsShowRpmError('Please enter an RPM value first.');
    return;
  }
  if (v < DS_RPM_CONFIG.min || v > DS_RPM_CONFIG.max) {
    dsShowRpmError(`RPM invalid. Please input a number between ${DS_RPM_CONFIG.min} – ${DS_RPM_CONFIG.max}.`);
    return;
  }

  // Success — lock in
  dsRpmSet = true;
  dsHideRpmError();
  const confirmBtn = document.getElementById('dsRpmConfirmBtn');
  confirmBtn.classList.add('success');
  confirmBtn.textContent = '✓ RPM Set';
  document.getElementById('dsRpmInputDisplay').classList.add('flash');
  playClickTone(800, 300);

  // Display value on the apparatus screen
  const screenDisplay = document.getElementById('dsRpmDisplay');
  document.getElementById('dsRpmDisplayValue').textContent = String(v);
  screenDisplay.classList.add('show');

  // Lock both RPM hotspots
  document.getElementById('dsRpmNumpadHotspot')?.classList.add('done');
  document.getElementById('dsRpmScreenHotspot')?.classList.add('done');

  // Advance progress bar + unlock Step 6
  document.getElementById('dsStep5')?.classList.remove('active');
  document.getElementById('dsStep5')?.classList.add('done');
  document.getElementById('dsStep6')?.classList.add('active');
  document.getElementById('dsTimeKnobHotspot')?.classList.remove('locked');
  document.getElementById('dsInstructionText').innerHTML =
    `✅ RPM set to <strong>${v}</strong>. Now click the <strong>knob</strong> on the apparatus to set the run time.`;

  // Auto-close after a brief pause
  setTimeout(() => {
    document.getElementById('modalDsRpm').classList.remove('open');
  }, 2000);
}

/* Keyboard input on RPM modal */
document.addEventListener('keydown', e => {
  const open = document.getElementById('modalDsRpm')?.classList.contains('open');
  if (!open || dsRpmSet) return;
  if (/^[0-9]$/.test(e.key))      dsRpmKey(e.key);
  else if (e.key === 'Backspace') dsRpmBack();
  else if (e.key === 'Enter')     dsRpmConfirm();
  else if (e.key === 'Escape')    dsCloseRpmModal();
});

/* ──────────────────────────────────────────────────────────────────────────
   STEP 6: SET TIME VIA KNOB POPUP
   ──────────────────────────────────────────────────────────────────────── */

const DS_TIME_CONFIG = {
  min: 5,
  max: 30,
};

let dsTimeValue   = 5;        // current time in seconds (default starts at min)
let dsTimeSet     = false;
let dsKnobAngle   = 0;        // visual knob rotation in deg
let dsTimeArrowInterval = null;
let dsTimeArrowTimeout  = null;

function dsOpenTimeModal() {
  if (dsTimeSet) return;
  dsTimeValue = 5;
  dsKnobAngle = 0;
  dsUpdateTimeUI();
  dsHideTimeError();
  document.getElementById('modalDsTime').classList.add('open');
}

function dsCloseTimeModal() {
  document.getElementById('modalDsTime').classList.remove('open');
}

function dsUpdateTimeUI() {
  document.getElementById('dsTimeReadoutValue').textContent = String(dsTimeValue);
  // Rotate knob: full sweep across the time range
  const range = DS_TIME_CONFIG.max - DS_TIME_CONFIG.min;
  const t     = (dsTimeValue - DS_TIME_CONFIG.min) / range;  // 0..1
  // Spin from 0° (min) to 270° (max)
  dsKnobAngle = t * 270;
  const knob  = document.getElementById('dsKnob');
  if (knob) knob.style.transform = `rotate(${dsKnobAngle}deg)`;
}

function dsBumpTime(dir) {
  if (dsTimeSet) return;
  dsHideTimeError();
  let v = dsTimeValue + dir;
  if (v < DS_TIME_CONFIG.min) v = DS_TIME_CONFIG.min;
  if (v > DS_TIME_CONFIG.max) v = DS_TIME_CONFIG.max;
  if (v === dsTimeValue) return;
  dsTimeValue = v;
  dsUpdateTimeUI();
  playClickTone(700, 500);
}

/* Hold-to-accelerate */
function dsStartTimeArrow(dir) {
  if (dsTimeSet) return;
  dsBumpTime(dir);
  let speed = 240;
  let count = 0;
  dsTimeArrowTimeout = setTimeout(function step() {
    dsBumpTime(dir);
    count++;
    if      (count > 20) speed = 40;
    else if (count > 10) speed = 80;
    else                 speed = 160;
    dsTimeArrowInterval = setTimeout(step, speed);
  }, 400);
}
function dsStopTimeArrow() {
  clearTimeout(dsTimeArrowTimeout);
  clearTimeout(dsTimeArrowInterval);
  dsTimeArrowInterval = null;
  dsTimeArrowTimeout  = null;
}

function dsShowTimeError(msg) {
  const err = document.getElementById('dsTimeError');
  document.getElementById('dsTimeErrorText').textContent = msg;
  err.classList.remove('hidden');
  const readout = document.getElementById('dsTimeReadout');
  readout.classList.add('shake');
  setTimeout(() => readout.classList.remove('shake'), 500);
}
function dsHideTimeError() {
  document.getElementById('dsTimeError').classList.add('hidden');
}

function dsTimeConfirm() {
  if (dsTimeSet) return;
  if (dsTimeValue < DS_TIME_CONFIG.min) {
    dsShowTimeError(`Time must be at least ${DS_TIME_CONFIG.min} seconds.`);
    return;
  }

  dsTimeSet = true;
  const confirmBtn = document.getElementById('dsTimeConfirmBtn');
  confirmBtn.classList.add('success');
  confirmBtn.textContent = '✓ Time Set';
  document.getElementById('dsTimeReadout').classList.add('flash');
  playClickTone(800, 300);

  // Show on apparatus screen
  const td = document.getElementById('dsTimeDisplay');
  document.getElementById('dsTimeDisplayValue').textContent = String(dsTimeValue);
  td.classList.add('show');

  // Lock knob hotspot
  document.getElementById('dsTimeKnobHotspot')?.classList.add('done');

  // Advance progress bar + unlock Step 7
  document.getElementById('dsStep6')?.classList.remove('active');
  document.getElementById('dsStep6')?.classList.add('done');
  document.getElementById('dsStep7')?.classList.add('active');
  document.getElementById('dsStartHotspot')?.classList.remove('locked');
  document.getElementById('dsInstructionText').innerHTML =
    `✅ Time set to <strong>${dsTimeValue} seconds</strong>. Click the <strong>start button</strong> to run the machine.`;

  // Auto-close
  setTimeout(() => {
    document.getElementById('modalDsTime').classList.remove('open');
  }, 2000);
}

/* Keyboard for time modal */
document.addEventListener('keydown', e => {
  const open = document.getElementById('modalDsTime')?.classList.contains('open');
  if (!open || dsTimeSet) return;
  if      (e.key === 'ArrowLeft')  dsBumpTime(-1);
  else if (e.key === 'ArrowRight') dsBumpTime(1);
  else if (e.key === 'Enter')      dsTimeConfirm();
  else if (e.key === 'Escape')     dsCloseTimeModal();
});

/* ──────────────────────────────────────────────────────────────────────────
   STEP 7: PRESS START — paddle rotates + timer counts down
   ──────────────────────────────────────────────────────────────────────── */

let dsRunning      = false;
let dsRunCountdown = 0;
let dsRunTimer     = null;

function dsPressStart() {
  if (dsRunning) return;
  if (!dsRpmSet || !dsTimeSet) return;
  dsRunning = true;

  // Mark hotspot done (stops pulse, blocks clicks)
  document.getElementById('dsStartHotspot')?.classList.add('done');

  // Click ripple feedback
  playClickTone(700, 400);

  document.getElementById('dsInstructionText').innerHTML =
    '⚙️ Machine running… please wait until the timer finishes.';

  // ── Paddle rotation tied to RPM ──
  // RPM = revolutions per minute → period (sec) = 60 / RPM
  // We exaggerate visually so even at 50 RPM you see clear motion.
  // Real-time period (s) = 60 / rpmValue
  // We'll use a faster animation than reality (×4) so it looks lively.
  const rpm   = dsRpmCurrent;
  const realPeriod = 60 / rpm;       // seconds for one revolution at given RPM
  const visualPeriod = realPeriod / 4; // speed up 4× for visual clarity
  const paddle = document.getElementById('dsPaddle');
  paddle.style.setProperty('--paddle-period', visualPeriod + 's');
  paddle.classList.add('spinning');

  // ── Countdown timer ──
  dsRunCountdown = dsTimeValue;
  // Update the on-screen time display every second
  const updateDisplay = () => {
    document.getElementById('dsTimeDisplayValue').textContent = String(dsRunCountdown);
  };
  updateDisplay();

  dsRunTimer = setInterval(() => {
    dsRunCountdown--;
    updateDisplay();
    if (dsRunCountdown <= 0) {
      // Stop the machine
      clearInterval(dsRunTimer);
      dsRunTimer = null;
      paddle.classList.remove('spinning');
      // Reset paddle blade to upright
      const img = paddle.querySelector('img');
      if (img) img.style.transform = '';
      dsRunning = false;

      // Tablet has now "dissolved" — fade it out
      const fallingTablet = document.getElementById('dsTabletFalling');
      if (fallingTablet) {
        fallingTablet.style.transition = 'opacity 1.2s ease-out';
        fallingTablet.style.opacity    = '0';
        // After fade completes, fully hide it
        setTimeout(() => {
          fallingTablet.style.display = 'none';
        }, 1300);
      }

      // Mark Step 7 done — advance to Step 8
      document.getElementById('dsStep7')?.classList.remove('active');
      document.getElementById('dsStep7')?.classList.add('done');
      document.getElementById('dsStep8')?.classList.add('active');
      document.getElementById('dsRackHotspot')?.classList.remove('locked');
      document.getElementById('dsInstructionText').innerHTML =
        '🎉 Machine done! Click the <strong>pipette rack</strong> to extract a sample.';
      playClickTone(900, 300);
      // UV-VIS unlock will happen at end of Step 10
    }
  }, 1000);
}

/* ──────────────────────────────────────────────────────────────────────────
   STEP 8: PIPETTE 1 — CHAMBER → FILTER
   ──────────────────────────────────────────────────────────────────────── */

let dsChamberLevel = 100;             // chamber liquid level (full after Step 2)
let dsPipette1Done = false;
let dsPipettePhase = 0;                // 0=idle, 1=picked-up, 2=dipped chamber, 3=done

function dsClickRack() {
  // Branch based on which step is active
  if (dsPipette3Done) return;

  if (dsPipette2Done && !dsPipette3Done) {
    // Step 10 — third pipette (volumetric → blank cuvette)
    if (dsPipettePhase !== 0) return;
    dsPipettePhase = 7;
    document.getElementById('dsRackHotspot')?.classList.add('done');

    // Swap rack background: 1 pipette → 0 pipettes
    document.getElementById('dsSceneBg').src = '/qcsim/Assets/VirtualLab/dissolution_bg_0.png';

    const pipette = document.getElementById('dsPipette');
    pipette.style.display = 'block';
    pipette.classList.remove('over-chamber', 'over-filter', 'over-filter-step9', 'over-sample', 'over-volumetric', 'over-blank', 'filled');

    document.getElementById('dsPipetteDropVolumetric').classList.add('show');

    document.getElementById('dsInstructionText').innerHTML =
      'Drag the <strong>pipette</strong> onto the volumetric flask to extract the blank.';
    playClickTone(700, 500);
    return;
  }

  if (dsPipette1Done && !dsPipette2Done) {
    // Step 9 — second pipette (filter → sample cuvette)
    if (dsPipettePhase !== 0) return;
    dsPipettePhase = 4;
    document.getElementById('dsRackHotspot')?.classList.add('done');

    // Swap rack background: 2 pipettes → 1 pipette
    document.getElementById('dsSceneBg').src = '/qcsim/Assets/VirtualLab/dissolution_bg_1.png';

    const pipette = document.getElementById('dsPipette');
    pipette.style.display = 'block';
    pipette.classList.remove('over-chamber', 'over-filter', 'over-filter-step9', 'over-sample', 'filled');

    document.getElementById('dsPipetteDropFilterStep9').classList.add('show');

    document.getElementById('dsInstructionText').innerHTML =
      'Drag the <strong>pipette</strong> onto the filter to extract the filtered sample.';
    playClickTone(700, 500);
    return;
  }

  // Default: Step 8 — first pipette (chamber → filter)
  if (dsPipette1Done) return;
  if (dsPipettePhase !== 0) return;

  dsPipettePhase = 1;
  document.getElementById('dsRackHotspot')?.classList.add('done');

  // Swap rack background: 3 pipettes → 2 pipettes
  document.getElementById('dsSceneBg').src = '/qcsim/Assets/VirtualLab/dissolution_bg_2.png';

  const pipette = document.getElementById('dsPipette');
  pipette.style.display = 'block';
  pipette.classList.remove('over-chamber', 'over-filter', 'over-filter-step9', 'over-sample', 'filled');

  document.getElementById('dsPipetteDropChamber').classList.add('show');

  document.getElementById('dsInstructionText').innerHTML =
    'Drag the <strong>pipette</strong> onto the dissolution chamber to extract a sample.';
  playClickTone(700, 500);
}

function dsPipetteDragStart(e) {
  // Allow drag in Step 8 (1-2), Step 9 (4-5), or Step 10 (7-8)
  const validPhase = dsPipettePhase === 1 || dsPipettePhase === 2 ||
                     dsPipettePhase === 4 || dsPipettePhase === 5 ||
                     dsPipettePhase === 7 || dsPipettePhase === 8;
  if (!validPhase) { e.preventDefault(); return; }
  e.dataTransfer.setData('text/plain', 'pipette');
  e.dataTransfer.effectAllowed = 'move';
  e.target.classList.add('dragging');
}
function dsPipetteDragEnd(e) {
  e.target.classList.remove('dragging');
}
function dsPipetteDragOver(e) {
  e.preventDefault();
  e.dataTransfer.dropEffect = 'move';
  e.currentTarget.classList.add('over');
}
function dsPipetteDragLeave(e) {
  e.currentTarget.classList.remove('over');
}

function dsPipetteDropOnChamber(e) {
  e.preventDefault();
  e.currentTarget.classList.remove('over');
  if (dsPipettePhase !== 1) return;
  if (e.dataTransfer.getData('text/plain') !== 'pipette') return;

  dsPipettePhase = 2;
  document.getElementById('dsPipetteDropChamber').classList.remove('show');

  // Animate pipette to chamber pose
  const pipette = document.getElementById('dsPipette');
  pipette.classList.add('over-chamber');

  // After tilt finishes, drain chamber 100% → 90%
  setTimeout(() => {
    // Pipette fills with liquid as it extracts
    document.getElementById('dsPipette').classList.add('filled');
    window.labAudio?.startPour();
    dsAnimateChamberDrain(100, 90, 700, () => {
      window.labAudio?.stopPour();
      // Transit pipette to filter pose
      pipette.classList.remove('over-chamber');
      pipette.classList.add('over-filter');

      // Show filter drop zone after transit
      setTimeout(() => {
        document.getElementById('dsPipetteDropFilter').classList.add('show');
        document.getElementById('dsInstructionText').innerHTML =
          'Now drag the <strong>pipette</strong> onto the filter.';
      }, 700);
    });
  }, 600);
}

function dsPipetteDropOnFilter(e) {
  e.preventDefault();
  e.currentTarget.classList.remove('over');
  if (dsPipettePhase !== 2) return;
  if (e.dataTransfer.getData('text/plain') !== 'pipette') return;

  dsPipettePhase = 3;
  document.getElementById('dsPipetteDropFilter').classList.remove('show');

  // Drain pipette as it pours into filter
  document.getElementById('dsPipette').classList.remove('filled');

  // Fill the filter (clip-path animation — empty → 75%)
  document.getElementById('dsFilterFilled').classList.add('filling');
  window.labAudio?.startPour();

  // After the fill animation completes, swap funnel PNG to beaker-only
  // and swap the filled (with funnel) to water-only
  setTimeout(() => {
    window.labAudio?.stopPour();
    document.getElementById('dsFilterEmpty').style.display     = 'none';
    document.getElementById('dsBeakerOnly').style.display      = 'block';
    document.getElementById('dsFilterFilled').style.display    = 'none';
    // Show water-only layer at the same 75% level
    const waterOnly = document.getElementById('dsFilterWaterOnly');
    waterOnly.style.display = 'block';
    waterOnly.classList.add('filling');
  }, 1100);

  // Pipette disappears after a short pause
  setTimeout(() => {
    document.getElementById('dsPipette').style.display = 'none';
    dsPipette1Done = true;
    dsPipettePhase = 0;
    document.getElementById('dsStep8')?.classList.remove('active');
    document.getElementById('dsStep8')?.classList.add('done');
    // Advance to Step 9 — re-unlock the rack (now conceptually 2 pipettes)
    document.getElementById('dsStep9')?.classList.add('active');
    document.getElementById('dsRackHotspot')?.classList.remove('done', 'locked');
    document.getElementById('dsInstructionText').innerHTML =
      '✅ Sample extracted to filter. Click the <strong>pipette rack</strong> for the next transfer.';
  }, 1100);
}

/* Animate chamber liquid drain via clip-path inset */
function dsAnimateChamberDrain(fromPct, toPct, duration, onDone) {
  const chamber = document.getElementById('dsChamberFilled');
  if (!chamber) { if (onDone) onDone(); return; }
  const startTime = performance.now();
  function tick(now) {
    const t   = Math.min(1, (now - startTime) / duration);
    const cur = fromPct + (toPct - fromPct) * t;
    dsChamberLevel = cur;
    chamber.style.clipPath = `inset(${100 - cur}% 0 0 0)`;
    if (t < 1) requestAnimationFrame(tick);
    else if (onDone) onDone();
  }
  requestAnimationFrame(tick);
}

/* ──────────────────────────────────────────────────────────────────────────
   STEP 9: PIPETTE 2 — FILTER → SAMPLE CUVETTE
   ──────────────────────────────────────────────────────────────────────── */

let dsPipette2Done = false;

function dsPipetteDropOnFilterStep9(e) {
  e.preventDefault();
  e.currentTarget.classList.remove('over');
  if (dsPipettePhase !== 4) return;
  if (e.dataTransfer.getData('text/plain') !== 'pipette') return;

  dsPipettePhase = 5;
  document.getElementById('dsPipetteDropFilterStep9').classList.remove('show');

  const pipette = document.getElementById('dsPipette');
  pipette.classList.add('over-filter-step9');

  // Pipette fills with liquid as it extracts; filter drains 75% → 0%
  setTimeout(() => {
    pipette.classList.add('filled');
    window.labAudio?.startPour();
    // Drain filter water-only PNG via clip-path
    dsAnimateFilterDrain(75, 0, 800, () => {
      window.labAudio?.stopPour();
      // Transit pipette to sample cuvette pose
      pipette.classList.remove('over-filter-step9');
      pipette.classList.add('over-sample');
      // Show sample cuvette drop zone after transit
      setTimeout(() => {
        document.getElementById('dsPipetteDropSample').classList.add('show');
        document.getElementById('dsInstructionText').innerHTML =
          'Now drag the <strong>pipette</strong> onto the sample cuvette.';
      }, 700);
    });
  }, 600);
}

function dsPipetteDropOnSample(e) {
  e.preventDefault();
  e.currentTarget.classList.remove('over');
  if (dsPipettePhase !== 5) return;
  if (e.dataTransfer.getData('text/plain') !== 'pipette') return;

  dsPipettePhase = 6;
  document.getElementById('dsPipetteDropSample').classList.remove('show');

  // Drain pipette as it pours
  document.getElementById('dsPipette').classList.remove('filled');

  // Fill sample cuvette (clip-path → 80%)
  document.getElementById('dsSampleCuvetteFilled').classList.add('filling');
  window.labAudio?.startPour();

  // Pipette disappears
  setTimeout(() => {
    window.labAudio?.stopPour();
    document.getElementById('dsPipette').style.display = 'none';
    dsPipette2Done = true;
    dsPipettePhase = 0;
    document.getElementById('dsStep9')?.classList.remove('active');
    document.getElementById('dsStep9')?.classList.add('done');
    // Advance to Step 10 — re-unlock the rack (last pipette)
    document.getElementById('dsStep10')?.classList.add('active');
    document.getElementById('dsRackHotspot')?.classList.remove('done', 'locked');
    document.getElementById('dsInstructionText').innerHTML =
      '✅ Sample cuvette filled. Click the <strong>pipette rack</strong> for the final transfer.';
  }, 1100);
}

/* Animate the filter water-only PNG draining via clip-path
   The water-only layer uses .filling class with inset(25% 0 0 0) for 75% level.
   To drain it, we override the clip-path inline. */
function dsAnimateFilterDrain(fromPct, toPct, duration, onDone) {
  const water = document.getElementById('dsFilterWaterOnly');
  if (!water) { if (onDone) onDone(); return; }
  // Remove .filling class so we can manually control clip-path
  water.classList.remove('filling');
  water.style.transition = 'clip-path .12s linear';
  const startTime = performance.now();
  function tick(now) {
    const t   = Math.min(1, (now - startTime) / duration);
    const cur = fromPct + (toPct - fromPct) * t;
    water.style.clipPath = `inset(${100 - cur}% 0 0 0)`;
    if (t < 1) requestAnimationFrame(tick);
    else if (onDone) onDone();
  }
  requestAnimationFrame(tick);
}

/* ──────────────────────────────────────────────────────────────────────────
   STEP 10: PIPETTE 3 — VOLUMETRIC → BLANK CUVETTE
   ──────────────────────────────────────────────────────────────────────── */

let dsPipette3Done = false;

function dsPipetteDropOnVolumetric(e) {
  e.preventDefault();
  e.currentTarget.classList.remove('over');
  if (dsPipettePhase !== 7) return;
  if (e.dataTransfer.getData('text/plain') !== 'pipette') return;

  dsPipettePhase = 8;
  document.getElementById('dsPipetteDropVolumetric').classList.remove('show');

  const pipette = document.getElementById('dsPipette');
  pipette.classList.add('over-volumetric');

  // Pipette fills, volumetric drains by 25 percentage points
  setTimeout(() => {
    pipette.classList.add('filled');
    window.labAudio?.startPour();
    const startLevel = dsVolLevel;
    const endLevel   = Math.max(0, dsVolLevel - 25);
    const startTime  = performance.now();
    const duration   = 800;
    function tick(now) {
      const t = Math.min(1, (now - startTime) / duration);
      dsVolLevel = startLevel + (endLevel - startLevel) * t;
      dsRender();
      if (t < 1) requestAnimationFrame(tick);
      else {
        window.labAudio?.stopPour();
        // Transit pipette to blank cuvette pose
        pipette.classList.remove('over-volumetric');
        pipette.classList.add('over-blank');
        setTimeout(() => {
          document.getElementById('dsPipetteDropBlank').classList.add('show');
          document.getElementById('dsInstructionText').innerHTML =
            'Now drag the <strong>pipette</strong> onto the blank cuvette.';
        }, 700);
      }
    }
    requestAnimationFrame(tick);
  }, 600);
}

function dsPipetteDropOnBlank(e) {
  e.preventDefault();
  e.currentTarget.classList.remove('over');
  if (dsPipettePhase !== 8) return;
  if (e.dataTransfer.getData('text/plain') !== 'pipette') return;

  dsPipettePhase = 9;
  document.getElementById('dsPipetteDropBlank').classList.remove('show');

  // Drain pipette as it pours
  document.getElementById('dsPipette').classList.remove('filled');

  // Fill blank cuvette (clip-path → 80%)
  document.getElementById('dsBlankCuvetteFilled').classList.add('filling');
  window.labAudio?.startPour();

  // Pipette disappears, simulation complete
  setTimeout(() => {
    window.labAudio?.stopPour();
    document.getElementById('dsPipette').style.display = 'none';
    dsPipette3Done = true;
    dsPipettePhase = 0;
    document.getElementById('dsStep10')?.classList.remove('active');
    document.getElementById('dsStep10')?.classList.add('done');
    document.getElementById('dsInstructionText').innerHTML =
      '🎉 <strong>Dissolution simulation complete!</strong> The UV-VIS Spectrophotometer is now unlocked.';
    playClickTone(900, 300);

    // Unlock the UV-VIS spectrophotometer
    if (typeof window.unlockUvvis === 'function') window.unlockUvvis();
  }, 1100);
}

/* ── INIT on first load ───────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', dsRender);

/* ──────────────────────────────────────────────────────────────────────────
   RESTART TOOL — fires labReset to put dissolution back to the start
   while keeping the modal open so the user can immediately try again.
   ──────────────────────────────────────────────────────────────────────── */
function dsRestartTool() {
  // Fire dissolution-only reset event
  window.dispatchEvent(new CustomEvent('labResetDissolution'));
  // Make sure the dissolution modal stays open
  document.getElementById('modalDissolution')?.classList.add('open');
  // Close any pour-related popups that may have been triggered
  document.getElementById('modalDsRpm')?.classList.remove('open');
  document.getElementById('modalDsTime')?.classList.remove('open');
  document.getElementById('modalDsOverflow')?.classList.remove('open');
}

/* ── HOOK INTO labResetDissolution (also fired by global labReset) ─────── */
window.addEventListener('labResetDissolution', () => {
  dsRoundLevel    = 100;
  dsVolLevel      = 0;
  dsIsPouring     = false;
  dsTransferDone  = false;
  dsArmed         = false;
  dsChamberDone   = false;
  dsContainerOpen = false;
  dsTabletPlaced  = false;
  if (dsPourTimer) { clearInterval(dsPourTimer); dsPourTimer = null; }
  document.getElementById('dsRoundFlask')?.classList.remove('done', 'tilted', 'tilted-chamber', 'armed');
  document.getElementById('dsRoundFlask')?.setAttribute('draggable', 'true');
  document.getElementById('dsStep1')?.classList.add('active');
  document.getElementById('dsStep1')?.classList.remove('done');
  document.getElementById('dsStep2')?.classList.remove('active', 'done');
  document.getElementById('dsStep3')?.classList.remove('active', 'done');
  document.getElementById('dsStep4')?.classList.remove('active', 'done');
  document.getElementById('modalDsOverflow')?.classList.remove('open');
  document.getElementById('dsInlineControls')?.classList.remove('show');
  document.getElementById('dsChamberDropZone')?.classList.remove('show');
  const chamberEl = document.getElementById('dsChamberFilled');
  if (chamberEl) {
    chamberEl.classList.remove('filling');
    chamberEl.style.clipPath  = '';   // clear any inline clip-path set by drain animation
    chamberEl.style.transition = '';
  }
  // Reset tablet container
  const tc = document.getElementById('dsTabletContainer');
  if (tc) tc.classList.remove('armed', 'opened');
  document.getElementById('dsTabletContainerClosed').style.display = '';
  document.getElementById('dsTabletContainerOpen').style.display   = 'none';
  // Hide draggable / falling tablet, hide tablet drop zone
  document.getElementById('dsTabletDraggable').style.display = 'none';
  // Fully reset the falling tablet — clear all inline styles from previous animations
  const fallingT = document.getElementById('dsTabletFalling');
  if (fallingT) {
    fallingT.style.display    = 'none';
    fallingT.style.opacity    = '';
    fallingT.style.top        = '';
    fallingT.style.left       = '';
    fallingT.style.transform  = '';
    fallingT.style.transition = '';
  }
  document.getElementById('dsTabletDropZone')?.classList.remove('show');
  // Reset volumetric drop zone
  const volZone = document.getElementById('dsVolumetricDropZone');
  if (volZone) volZone.style.display = '';

  // Reset Step 5 state
  dsRpmInput   = '';
  dsRpmCurrent = 0;
  dsRpmSet     = false;
  document.getElementById('dsRpmNumpadHotspot')?.classList.add('locked');
  document.getElementById('dsRpmNumpadHotspot')?.classList.remove('done');
  document.getElementById('dsRpmScreenHotspot')?.classList.add('locked');
  document.getElementById('dsRpmScreenHotspot')?.classList.remove('done');
  document.getElementById('dsRpmDisplay')?.classList.remove('show');
  document.getElementById('dsRpmDisplayValue').textContent = '000';
  document.getElementById('modalDsRpm')?.classList.remove('open');
  const rpmBtn = document.getElementById('dsRpmConfirmBtn');
  if (rpmBtn) {
    rpmBtn.classList.remove('success');
    rpmBtn.textContent = 'Confirm RPM';
  }
  document.getElementById('dsRpmInputValue').textContent = '0';
  document.getElementById('dsRpmInputDisplay')?.classList.remove('flash');
  document.getElementById('dsRpmError')?.classList.add('hidden');
  document.getElementById('dsStep5')?.classList.remove('active', 'done');

  // Reset Step 6 state
  dsTimeValue = 5;
  dsTimeSet   = false;
  dsKnobAngle = 0;
  if (dsTimeArrowTimeout)  { clearTimeout(dsTimeArrowTimeout);  dsTimeArrowTimeout = null; }
  if (dsTimeArrowInterval) { clearTimeout(dsTimeArrowInterval); dsTimeArrowInterval = null; }
  document.getElementById('dsTimeKnobHotspot')?.classList.add('locked');
  document.getElementById('dsTimeKnobHotspot')?.classList.remove('done');
  document.getElementById('dsTimeDisplay')?.classList.remove('show');
  document.getElementById('dsTimeDisplayValue').textContent = '00';
  document.getElementById('modalDsTime')?.classList.remove('open');
  const timeBtn = document.getElementById('dsTimeConfirmBtn');
  if (timeBtn) {
    timeBtn.classList.remove('success');
    timeBtn.textContent = 'Confirm Time';
  }
  document.getElementById('dsTimeReadoutValue').textContent = '5';
  const knobEl = document.getElementById('dsKnob');
  if (knobEl) knobEl.style.transform = 'rotate(0deg)';
  document.getElementById('dsTimeReadout')?.classList.remove('flash', 'shake');
  document.getElementById('dsTimeError')?.classList.add('hidden');
  document.getElementById('dsStep6')?.classList.remove('active', 'done');

  // Reset Step 7 state
  dsRunning      = false;
  dsRunCountdown = 0;
  if (dsRunTimer) { clearInterval(dsRunTimer); dsRunTimer = null; }
  document.getElementById('dsStartHotspot')?.classList.add('locked');
  document.getElementById('dsStartHotspot')?.classList.remove('done');
  const paddle = document.getElementById('dsPaddle');
  if (paddle) {
    paddle.classList.remove('spinning');
    const img = paddle.querySelector('img');
    if (img) img.style.transform = '';
  }
  document.getElementById('dsStep7')?.classList.remove('active', 'done');

  // Reset Step 8 state
  dsPipette1Done = false;
  dsPipette2Done = false;
  dsPipette3Done = false;
  dsPipettePhase = 0;
  dsChamberLevel = 100;
  // Restore rack background to 3 pipettes
  const sceneBg = document.getElementById('dsSceneBg');
  if (sceneBg) sceneBg.src = '/qcsim/Assets/VirtualLab/dissolution_bg.png';
  document.getElementById('dsRackHotspot')?.classList.add('locked');
  document.getElementById('dsRackHotspot')?.classList.remove('done');
  const pip = document.getElementById('dsPipette');
  if (pip) {
    pip.style.display = 'none';
    pip.classList.remove('over-chamber', 'over-filter', 'over-filter-step9', 'over-sample', 'over-volumetric', 'over-blank', 'dragging', 'filled');
  }
  document.getElementById('dsPipetteDropChamber')?.classList.remove('show');
  document.getElementById('dsPipetteDropFilter')?.classList.remove('show');
  document.getElementById('dsPipetteDropFilterStep9')?.classList.remove('show');
  document.getElementById('dsPipetteDropSample')?.classList.remove('show');
  document.getElementById('dsPipetteDropVolumetric')?.classList.remove('show');
  document.getElementById('dsPipetteDropBlank')?.classList.remove('show');
  document.getElementById('dsFilterFilled')?.classList.remove('filling');
  // Restore filter+funnel PNG visibility, hide beaker-only and water-only
  const fe = document.getElementById('dsFilterEmpty');
  const bo = document.getElementById('dsBeakerOnly');
  const ff = document.getElementById('dsFilterFilled');
  const fw = document.getElementById('dsFilterWaterOnly');
  if (fe) fe.style.display = '';
  if (bo) bo.style.display = 'none';
  if (ff) ff.style.display = '';
  if (fw) {
    fw.style.display = 'none';
    fw.classList.remove('filling');
    fw.style.clipPath = '';
    fw.style.transition = '';
  }
  // Reset sample cuvette
  const scf = document.getElementById('dsSampleCuvetteFilled');
  if (scf) scf.classList.remove('filling');
  document.getElementById('dsSampleCuvette')?.classList.remove('show');
  // Reset blank cuvette
  const bcf = document.getElementById('dsBlankCuvetteFilled');
  if (bcf) bcf.classList.remove('filling');
  document.getElementById('dsBlankCuvette')?.classList.remove('show');

  document.getElementById('dsStep8')?.classList.remove('active', 'done');
  document.getElementById('dsStep9')?.classList.remove('active', 'done');
  document.getElementById('dsStep10')?.classList.remove('active', 'done');

  // Restore round flask visibility (in case it was hidden)
  const rf = document.getElementById('dsRoundFlask');
  if (rf) {
    rf.style.display    = '';
    rf.style.opacity    = '';
    rf.style.transition = '';
  }

  const instr = document.getElementById('dsInstructionText');
  if (instr) instr.innerHTML =
    'Drag the <strong>round flask</strong> onto the <strong>volumetric flask</strong> to begin pouring.';
  dsRender();
});
</script>
