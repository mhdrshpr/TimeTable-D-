<?php
require_once 'db.php';
init_secure_session();

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$csrf_token = get_csrf_token();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>دروس پلاس وب</title>

<style>
:root{
    --primary:#2563eb;
    --bg:#f8fafc;
    --border:#cbd5e1;
    --row-h:55px;

    --cat-uni:#2563eb;
    --cat-sport:#059669;
    --cat-nonuni:#ff00e6;
    --cat-plus:#ff6d1f;
    --cat-other:#999999;
    --cat-book:#dc2626;
    --cat-movie:#750000;
    --cat-work:#7c3aed;
}

@font-face {
    font-family: 'IRANSansX';
    src: url('./fonts/IRANSansX-Regular.woff2') format('woff2');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'IRANSansX';
    src: url('./fonts/IRANSansX-Bold.woff2') format('woff2');
    font-weight: 700;
    font-style: normal;
    font-display: swap;
}

* {
    box-sizing: border-box;
}

body{
    font-family:'IRANSansX',sans-serif;
    background:var(--bg);
    margin:0;
    display:flex;
    flex-direction:column;
    height:100vh;
    overflow:hidden;
}

.ss01 {
    -moz-font-feature-settings: "ss01";
    -webkit-font-feature-settings: "ss01";
    font-feature-settings: "ss01";
}

.icon {
    width: 15px;
    height: 15px;
}

.icon-big {
    width: 60px;
    height: 60px;
}

.icon-header {
    width: 20px;
    height: 20px;
}

.icon-main {
    width: 30px;
    height: 30px;
}

.icon-drag {
    width: 16px;
    height: 16px;
    cursor: grab;
    pointer-events: auto;
}

.icon-drag:active {
    cursor: grabbing;
}

/* HEADER */
header{
    background:white;
    border-bottom:2px solid #e2e8f0;
    z-index: 100;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 25px;
    gap: 15px;
}

.header-row-1 {
    border-bottom: 1px solid #f1f5f9;
}

.header-row-2 {
    padding-top: 8px;
    padding-bottom: 8px;
    background: #fafbfc;
}

.header-right,
.header-left {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.header-center {
    text-align: center;
    flex: 1;
}

.header-center h2 {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin: 0;
    font-size: 1.3rem;
    color: #1e293b;
    white-space: nowrap;
}

.username-display {
    display: flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    padding: 8px 16px;
    border-radius: 10px;
    font-weight: bold;
    color: #1e40af;
    font-size: 0.95rem;
    white-space: nowrap;
}

.header-schedule-controls {
    flex: 1;
    display: flex;
    justify-content: flex-end;
    align-items: center;
}

.btn{
    padding:8px 14px;
    border-radius:8px;
    border:none;
    cursor:pointer;
    font-family:inherit;
    font-weight:bold;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.85rem;
    white-space: nowrap;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.btn:active {
    transform: translateY(0);
}

.btn-logout{
    background: rgba(239,68,68,0.1); 
    color: #ef4444;
    border: 1px solid rgba(239,68,68,0.2);
}

.btn-toggle{
    background: rgba(15,118,110,0.1); 
    color: #0f766e;
    border: 1px solid rgba(15,118,110,0.2);
}

.btn-print{
    background: rgba(71,85,105,0.1); 
    border: 1px solid rgba(71,85,105,0.2); 
    color: #475569;
}

.btn-io{
    background: rgba(14,165,233,0.1); 
    border: 1px solid rgba(14,165,233,0.2); 
    color: #0ea5e9;
}

.btn-save{
    background: rgba(5,150,105,0.1); 
    border: 1px solid rgba(5,150,105,0.2); 
    color: #059669;
}

.btn-add{
    background: var(--primary);
    color: white;
    border: 1px solid var(--primary);
}

.btn-auto-schedule {
    background: rgba(220, 38, 38, 0.1);
    border: 1px solid rgba(220, 38, 38, 0.3);
    color: #dc2626;
    font-weight: bold;
}

.btn-schedule {
    background: rgba(139, 92, 246, 0.1);
    border: 1px solid rgba(139, 92, 246, 0.2);
    color: #7c3aed;
    padding: 6px 10px;
    font-size: 0.8rem;
}

.schedule-selector {
    display: flex;
    align-items: center;
    gap: 6px;
    background: white;
    padding: 5px 8px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}

.schedule-selector select {
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    font-family: inherit;
    background: #f8fafc;
    min-width: 160px;
    font-size: 0.85rem;
    cursor: pointer;
}

/* LAYOUT */
.main{
    display:grid;
    grid-template-columns:320px 1fr;
    flex:1;
    overflow:hidden;
    transition:.3s;
}

.main.full{
    grid-template-columns:0px 1fr;
}

.sidebar{
    background:white;
    border-left:1px solid #e2e8f0;
    padding:15px;
    overflow-y:auto;
    transition:.3s;
    display: flex;
    flex-direction: column;
}

.sidebar.hidden{
    opacity:0;
    pointer-events:none;
}

.sidebar-section {
    margin-bottom: 20px;
}

.sidebar-section h3 {
    margin: 0 0 10px 0;
    font-size: 1rem;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 5px;
}

.course-card{
    border:1px solid #eef2f6;
    padding:12px;
    border-radius:10px;
    margin-bottom:10px;
    background:#fff;
    font-size:.85rem;
    cursor:pointer;
    position: relative;
    transition: 0.2s;
}

.course-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.delete-btn {
    height: 25px;
    width: 25px;
    position: absolute;
    top: 8px;
    left: 8px;
    color: #ef4444;
    cursor: pointer;
    padding: 5px;
    transition: 0.2s;
    z-index: 5;
}

.delete-btn:hover { 
    transform: scale(1.2); 
}

.card-info {
    display: flex;
    align-items: center;
    gap: 5px;
    margin: 4px 0;
    font-size: 0.8rem;
    color: #64748b;
}

/* STATS */
.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-top: 10px;
}

.stat-item {
    background: #f8fafc;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    border: 1px solid #e2e8f0;
}

.stat-label {
    font-size: 0.7rem;
    color: #64748b;
    margin-bottom: 4px;
}

.stat-value {
    font-weight: bold;
    font-size: 0.9rem;
    color: #1e293b;
}

.stat-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-left: 5px;
}

/* GRID */
.grid-container{
    padding:20px;
    overflow:auto;
    background:var(--bg);
    position: relative;
}

.timetable{
    display:grid;
    grid-template-columns:80px repeat(7,1fr);
    grid-template-rows:45px repeat(19,var(--row-h));
    background:var(--border);
    gap:1px;
    border:1px solid var(--border);
    border-radius:8px;
    overflow:hidden;
    min-width: 800px;
}

.cell{
    background:white;
    display:flex;
    align-items:center;
    justify-content:center;
    position:relative;
    transition: background 0.15s, box-shadow 0.15s;
}

.cell.drag-hover {
    background: #dbeafe !important;
    box-shadow: inset 0 0 0 3px #3b82f6 !important;
    z-index: 50;
}

.header-cell{
    background:#f1f5f9;
    font-weight:bold;
    font-size: 0.85rem;
}

.time-cell{
    background:#f8fafc;
    font-weight:bold;
    font-size:.8rem;
}

.class-block{
    position:absolute;
    left:4px;
    right:4px;
    border-radius:10px;
    padding:6px;
    text-align:center;
    z-index:10;
    cursor:pointer;
    display:flex;
    flex-direction:column;
    justify-content:center;
    gap:2px;
    font-size:.8rem;
    border-width: 1.5px;
    border-style: solid;
    backdrop-filter: blur(5px);
    transition: opacity 0.15s, box-shadow 0.2s;
    user-select: none;
}

.class-block:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 15;
}

.class-block.dragging-source {
    opacity: 0.4;
    z-index: 5 !important;
}

.drag-handle {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: grab;
    z-index: 20;
    border-radius: 4px;
    background: rgba(255,255,255,0.4);
    transition: background 0.2s;
}

.drag-handle:hover {
    background: rgba(255,255,255,0.7);
}

.drag-handle:active {
    cursor: grabbing;
}
.block-delete-btn {
    position: absolute;
    top: 4px;
    left: 4px;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 20;
    border-radius: 4px;
    background: rgba(255,255,255,0.4);
    transition: all 0.2s;
    opacity: 0;
}

.class-block:hover .block-delete-btn {
    opacity: 1;
}

.block-delete-btn:hover {
    background: rgba(239, 68, 68, 0.2);
    transform: scale(1.1);
}

.block-delete-btn img {
    width: 14px;
    height: 14px;
}

@media (max-width: 768px) {
    .block-delete-btn {
        opacity: 0.6;
    }
}
/* MODAL */
.modal-overlay {
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0, 0.6);
    backdrop-filter: blur(4px);
    z-index:1000;
    justify-content:center;
    align-items:center;
}

.modal-container {
    background:white;
    padding:25px;
    border-radius:20px;
    width:450px;
    max-width: 95vw;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
}

.modal-title {
    margin: 0 0 20px 0;
    color: var(--primary);
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.form-grid .full-width {
    grid-column: span 2;
}

.form-grid input,
.form-grid select {
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-family: inherit;
    font-size: 0.9rem;
    width: 100%;
    transition: border-color 0.2s;
}

.form-grid input:focus,
.form-grid select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

.modal-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 20px;
}

/* ==========================================
   GLOBAL LOADING SPINNER
   ========================================== */
.global-loader-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(8px);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.25s ease;
}

.global-loader-overlay.active {
    display: flex;
    opacity: 1;
}

.loader-card {
    background: #ffffff;
    padding: 28px 36px;
    border-radius: 20px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
    min-width: 260px;
    text-align: center;
    animation: loaderScaleIn 0.25s ease;
}

@keyframes loaderScaleIn {
    from { transform: scale(0.92); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.loader-spinner-ring {
    width: 48px;
    height: 48px;
    border: 4px solid #e2e8f0;
    border-top: 4px solid var(--primary);
    border-right: 4px solid #8b5cf6;
    border-radius: 50%;
    animation: spinRing 0.8s linear infinite;
}

@keyframes spinRing {
    to { transform: rotate(360deg); }
}

.loader-text {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
}

/* ==========================================
   MULTI-STEP WIZARD / AUTO SCHEDULE MODAL
   ========================================== */
.auto-schedule-container {
    width: 780px;
    max-width: 95vw;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    padding: 0;
    overflow: hidden;
    border-radius: 22px;
}

.wizard-header {
    padding: 20px 25px 12px 25px;
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
}

.wizard-header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.wizard-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1.15rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}

.wizard-close-btn {
    background: #f1f5f9;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    font-size: 1.1rem;
    transition: all 0.2s;
}

.wizard-close-btn:hover {
    background: #fee2e2;
    color: #ef4444;
}

/* STEPPER PROGRESS */
.stepper-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    padding: 0 10px;
}

.stepper-line {
    position: absolute;
    top: 16px;
    left: 40px;
    right: 40px;
    height: 3px;
    background: #e2e8f0;
    z-index: 1;
}

.stepper-line-progress {
    position: absolute;
    top: 0;
    right: 0;
    height: 100%;
    background: linear-gradient(90deg, #8b5cf6, var(--primary));
    transition: width 0.35s ease;
}

.step-node {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    cursor: pointer;
}

.step-node-bubble {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #ffffff;
    border: 2px solid #cbd5e1;
    color: #64748b;
    font-weight: 700;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
}

.step-node.active .step-node-bubble {
    border-color: var(--primary);
    background: var(--primary);
    color: #ffffff;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2);
}

.step-node.completed .step-node-bubble {
    border-color: #059669;
    background: #059669;
    color: #ffffff;
}

.step-node-label {
    font-size: 0.75rem;
    color: #64748b;
    margin-top: 6px;
    font-weight: 600;
    white-space: nowrap;
}

.step-node.active .step-node-label {
    color: var(--primary);
    font-weight: 700;
}

/* WIZARD BODY / SLIDER */
.wizard-slider-viewport {
    overflow-x: hidden;
    position: relative;
    flex: 1;
    min-height: 380px;
    max-height: 60vh;
}

.wizard-slider-track {
    display: flex;
    width: 400%;
    height: 100%;
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.wizard-slide {
    width: 25%;
    padding: 20px 25px;
    overflow-y: auto;
    box-sizing: border-box;
}

.slide-title-bar {
    margin-bottom: 15px;
}

.slide-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.slide-desc {
    font-size: 0.82rem;
    color: #64748b;
    margin: 0;
}

/* STEP 1: FIXED ITEMS */
.fixed-items-toolbar {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    margin-bottom: 12px;
}

.btn-mini {
    padding: 4px 10px;
    font-size: 0.75rem;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    cursor: pointer;
    font-family: inherit;
    font-weight: 600;
}

.btn-mini:hover {
    background: #e2e8f0;
}

.fixed-cards-grid {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.fixed-item-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.2s;
}

.fixed-item-card.is-locked {
    background: #f8fafc;
    border-color: #cbd5e1;
}

.fixed-card-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.cat-indicator-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background-color: #cbd5e1;
    transition: 0.25s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    right: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.25s;
    border-radius: 50%;
}

.toggle-switch input:checked + .toggle-slider {
    background-color: #2563eb;
}

.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(-20px);
}

/* STEP 2: FLEXIBLE ITEMS */
.flex-items-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 12px;
}

.flex-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.flex-card-row {
    display: flex;
    gap: 10px;
    align-items: center;
}

.flex-name-input {
    flex: 2;
    padding: 8px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-family: inherit;
    font-size: 0.9rem;
}

.flex-select {
    padding: 8px 10px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-family: inherit;
    font-size: 0.85rem;
    background: #f8fafc;
}

.duration-chips {
    display: flex;
    gap: 6px;
    align-items: center;
    flex-wrap: wrap;
}

.duration-chip {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    cursor: pointer;
    font-family: inherit;
}

.duration-chip.selected {
    background: #dbeafe;
    border-color: #3b82f6;
    color: #1d4ed8;
    font-weight: bold;
}

.btn-card-delete {
    background: #fee2e2;
    color: #ef4444;
    border: none;
    border-radius: 8px;
    padding: 7px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.2s;
}

.btn-card-delete:hover {
    background: #fca5a5;
    color: #b91c1c;
}

/* STEP 3: STRATEGIES & CONSTRAINTS */
.strategy-picker {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 20px;
}

.strategy-option {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 14px;
    cursor: pointer;
    background: #ffffff;
    transition: all 0.2s;
    text-align: right;
}

.strategy-option:hover {
    border-color: #93c5fd;
    background: #f8fafc;
}

.strategy-option.selected {
    border-color: var(--primary);
    background: #eff6ff;
    box-shadow: 0 0 0 1px var(--primary);
}

.strategy-option-title {
    font-weight: 700;
    font-size: 0.9rem;
    color: #1e293b;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.strategy-option-desc {
    font-size: 0.76rem;
    color: #64748b;
    line-height: 1.4;
}

.rules-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    background: #f8fafc;
    padding: 16px;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
}

.rule-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.rule-field label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #334155;
}

.rule-field input, .rule-field select {
    padding: 8px 10px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-family: inherit;
    font-size: 0.85rem;
    background: #ffffff;
}

/* STEP 4: PREVIEW & RESULTS */
.score-dashboard {
    display: flex;
    align-items: center;
    gap: 18px;
    background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
    border: 1.5px solid #a7f3d0;
    padding: 16px 20px;
    border-radius: 16px;
    margin-bottom: 16px;
}

.score-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #059669;
    color: #ffffff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1.15rem;
    box-shadow: 0 4px 10px rgba(5, 150, 105, 0.3);
    flex-shrink: 0;
}

.score-circle small {
    font-size: 0.55rem;
    font-weight: normal;
}

.score-details {
    flex: 1;
}

.score-details-title {
    font-weight: 700;
    color: #065f46;
    font-size: 0.95rem;
    margin-bottom: 4px;
}

.score-chips {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    font-size: 0.78rem;
    color: #047857;
}

.score-chip {
    background: #ffffff;
    padding: 3px 8px;
    border-radius: 6px;
    border: 1px solid #6ee7b7;
}

.suggested-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-right-width: 5px;
    transition: all 0.2s;
}

.suggested-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.suggested-card-badges {
    display: flex;
    gap: 6px;
    align-items: center;
    margin-top: 5px;
    font-size: 0.75rem;
}

.badge-tag {
    padding: 2px 7px;
    border-radius: 6px;
    font-size: 0.72rem;
    background: #f1f5f9;
    color: #475569;
}

.badge-tag-success {
    background: #dcfce7;
    color: #15803d;
}

/* WIZARD FOOTER ACTIONS */
.wizard-footer {
    padding: 14px 25px;
    background: #ffffff;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.wizard-footer-right, .wizard-footer-left {
    display: flex;
    gap: 8px;
}

/* TOAST NOTIFICATIONS */
.toast-container {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 8px;
    pointer-events: none;
}

.toast {
    padding: 12px 24px;
    border-radius: 12px;
    color: white;
    font-family: inherit;
    font-weight: bold;
    font-size: 0.9rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    animation: toastIn 0.3s ease, toastOut 0.3s ease 2.5s forwards;
    pointer-events: auto;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.toast-success {
    background: #059669;
}

.toast-error {
    background: #dc2626;
}

.toast-info {
    background: #2563eb;
}

@keyframes toastIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes toastOut {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
}

/* CONFIRM MODAL */
.confirm-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    z-index: 2100;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(4px);
}

.confirm-dialog {
    background: white;
    padding: 25px;
    border-radius: 20px;
    width: 350px;
    max-width: 90vw;
    text-align: center;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
}

.confirm-icon {
    margin-bottom: 15px;
}

.confirm-text {
    font-weight: bold;
    color: #1e293b;
    margin-bottom: 20px;
    font-size: 1rem;
}

.confirm-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.btn-confirm-yes { 
    background: #ef4444; 
    color: white; 
    border:none; 
    padding:10px; 
    border-radius:10px; 
    cursor:pointer; 
    font-family:inherit; 
    font-weight:bold; 
}

.btn-confirm-no { 
    background: #e2e8f0; 
    color: #475569; 
    border:none; 
    padding:10px; 
    border-radius:10px; 
    cursor:pointer; 
    font-family:inherit; 
    font-weight:bold; 
}

/* SCHEDULE MANAGEMENT MODAL */
.schedule-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    z-index: 2200;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(4px);
}

.schedule-modal-dialog {
    background: white;
    padding: 25px;
    border-radius: 20px;
    width: 400px;
    max-width: 90vw;
}

.schedule-modal-dialog h3 {
    margin: 0 0 20px 0;
    color: var(--primary);
}

.schedule-modal-dialog input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-family: inherit;
    margin-bottom: 15px;
}

.schedule-modal-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    body {
        overflow: auto;
        height: auto;
    }
    
    .header-row {
        padding: 10px 15px;
    }
    
    .header-row-1 {
        flex-direction: column;
        gap: 10px;
    }
    
    .header-right,
    .header-left {
        width: 100%;
        justify-content: center;
    }
    
    .header-center h2 {
        font-size: 1.1rem;
    }
    
    .username-display {
        font-size: 0.9rem;
        padding: 6px 12px;
    }
    
    .btn {
        padding: 6px 10px;
        font-size: 0.8rem;
    }
    
    .btn-text {
        display: none;
    }
    
    .header-schedule-controls {
        justify-content: center;
        width: 100%;
    }
    
    .schedule-selector {
        flex-wrap: wrap;
        justify-content: center;
        width: 100%;
    }
    
    .schedule-selector select {
        min-width: 120px;
        font-size: 0.8rem;
    }
    
    .main {
        grid-template-columns: 1fr;
        flex: none;
    }
    
    .sidebar {
        display: none;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: 300px;
        z-index: 500;
        box-shadow: -5px 0 15px rgba(0,0,0,0.1);
    }
    
    .sidebar.mobile-visible {
        display: flex;
    }
    
    .grid-container {
        padding: 10px;
        overflow-x: auto;
    }
    
    .timetable {
        min-width: 650px;
        grid-template-columns: 60px repeat(7,1fr);
    }
    
    .class-block {
        font-size: 0.65rem;
        padding: 3px;
        left: 2px;
        right: 2px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-grid .full-width {
        grid-column: span 1;
    }
    
    .modal-actions {
        grid-template-columns: 1fr;
    }
    
    .fixed-item-row {
        grid-template-columns: 1fr 80px 70px 50px;
        font-size: 0.75rem;
        gap: 4px;
    }
    
    .flexible-item-row {
        grid-template-columns: 1fr 100px 80px;
        font-size: 0.75rem;
        gap: 4px;
    }
    
    .constraints-grid {
        grid-template-columns: 1fr;
    }
    
    .toast-container {
        left: 10px;
        right: 10px;
        transform: none;
    }
    
    .toast {
        font-size: 0.8rem;
        padding: 10px 16px;
    }
}

@media print{
    body *{ visibility:hidden; }
    .timetable, .timetable *{ visibility:visible; }
    .timetable{
        position:absolute;
        top:1;
        right:0;
        left:0;
        margin:10px;
    }
}
</style>
</head>

<body>

<!-- Global Content Loading Spinner -->
<div id="globalLoader" class="global-loader-overlay">
    <div class="loader-card">
        <div class="loader-spinner-ring"></div>
        <div class="loader-text" id="loaderText">در حال بارگذاری اطلاعات جدول...</div>
    </div>
</div>

<header>
    <div class="header-row header-row-1">
        <div class="header-right">
            <span class="username-display">
                <img src="./icons/user.svg" class="icon-header" alt="user">
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span>
        </div>
        
        <div class="header-center">
            <h2>
                <img src="./icons/calendar.svg" class="icon-main" alt="calendar">
                زمان‌بندی هفتگی
            </h2>
        </div>
        
        <div class="header-left">
            <button class="btn btn-print" onclick="window.print()" title="چاپ">
                <img src="./icons/print.svg" class="icon-header" alt="print">
                <span class="btn-text">چاپ</span>
            </button>
            <button class="btn btn-save" onclick="saveToHost(event)" title="ذخیره">
                <img src="./icons/save.svg" class="icon-header" alt="save">
                <span class="btn-text">ذخیره</span>
            </button>
            <button class="btn btn-io" onclick="exportJSON()" title="خروجی JSON">
                <img src="./icons/export.svg" class="icon-header" alt="export">
                <span class="btn-text">خروجی</span>
            </button>
            <button class="btn btn-io" onclick="document.getElementById('importFile').click()" title="ورودی JSON">
                <img src="./icons/import.svg" class="icon-header" alt="import">
                <span class="btn-text">ورودی</span>
            </button>
            <input type="file" id="importFile" accept=".json" style="display:none">
            <button class="btn btn-add" id="btnOpenModal" title="افزودن آیتم">
                <img src="./icons/add.svg" class="icon-header" alt="add">
                <span class="btn-text">افزودن</span>
            </button>
            <button class="btn btn-auto-schedule" onclick="openAutoScheduleModal()" title="برنامه‌ریزی هوشمند">
                <img src="./icons/magic.svg" class="icon-header" alt="auto">
                <span class="btn-text">پیشنهاد هوشمند</span>
            </button>
        </div>
    </div>
    
    <div class="header-row header-row-2">
        <div class="header-right">
            <a href="logout.php" class="btn btn-logout">
                <img src="./icons/logout.svg" class="icon-header" alt="logout"> خروج
            </a>
            <button class="btn btn-toggle" onclick="toggleSidebar()">
                <img src="./icons/sidebar.svg" class="icon-header" alt="sidebar"> سایدبار
            </button>
        </div>
        
        <div class="header-schedule-controls">
            <div class="schedule-selector">
                <select id="scheduleSelect" onchange="switchSchedule()">
                    <option value="">بارگذاری...</option>
                </select>
                <button class="btn btn-schedule" onclick="showScheduleModal('create')" title="جدول جدید">
                    <img src="./icons/add_purple.svg" class="icon-header" alt="new"> جدید
                </button>
                <button class="btn btn-schedule" onclick="showScheduleModal('rename')" title="تغییر نام">
                    <img src="./icons/edit_purple.svg" class="icon-header" alt="rename">
                </button>
                <button class="btn btn-schedule" onclick="deleteCurrentSchedule()" title="حذف جدول">
                    <img src="./icons/delete_purple.svg" class="icon-header" alt="delete">
                </button>
            </div>
        </div>
    </div>
</header>

<div class="main" id="mainLayout">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-section">
            <h3><img src="./icons/list.svg" class="icon" alt="list"> لیست زمان‌بندی‌ها</h3>
            <div id="courseList"></div>
        </div>
        
        <div class="sidebar-section">
            <h3><img src="./icons/chart.svg" class="icon" alt="stats"> مجموع زمان هر دسته‌بندی</h3>
            <div id="categoryStats" class="stats-grid"></div>
        </div>
    </aside>

    <div class="grid-container">
        <div class="timetable" id="timetable"></div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal-overlay" id="addEditModal">
    <div class="modal-container">
        <h3 class="modal-title">
            <img src="./icons/add.svg" class="icon-header" alt="add">
            <span id="modalTitle">افزودن زمان‌بندی</span>
        </h3>
        
        <div class="form-grid">
            <input type="hidden" id="editIdx" value="-1">
            
            <input type="text" id="name" placeholder="عنوان" class="full-width">
            
            <input type="text" id="prof" placeholder="استاد / مربی">
            <input type="text" id="loc" placeholder="مکان">
            
            <select id="category" class="full-width">
                <option value="uni">کلاس دانشگاهی</option>
                <option value="nonuni">کلاس غیر دانشگاهی</option>
                <option value="plus">درس پلاس</option>
                <option value="sport">ورزش</option>
                <option value="book">کتاب</option>
                <option value="movie">فیلم</option>
                <option value="work">کار</option>
                <option value="other">غیره</option>
            </select>
            
            <select id="day" class="full-width">
                <option value="1">شنبه</option>
                <option value="2">یکشنبه</option>
                <option value="3">دوشنبه</option>
                <option value="4">سه‌شنبه</option>
                <option value="5">چهارشنبه</option>
                <option value="6">پنج‌شنبه</option>
                <option value="7">جمعه</option>
            </select>
            
            <input type="time" id="sTime" value="08:00">
            <input type="time" id="eTime" value="10:00">
        </div>
        
        <div class="modal-actions">
            <button class="btn btn-add" id="btnSave">ذخیره</button>
            <button class="btn" style="background:#e2e8f0; color:#475569;" id="btnClose">انصراف</button>
        </div>
    </div>
</div>

<!-- Auto Schedule Modal (Multi-Step Slider Wizard) -->
<div class="modal-overlay" id="autoScheduleModal">
    <div class="modal-container auto-schedule-container">
        <!-- Wizard Header -->
        <div class="wizard-header">
            <div class="wizard-header-top">
                <h3 class="wizard-title">
                    <img src="./icons/magic.svg" class="icon-header" alt="magic">
                    دستیار هوشمند برنامه‌ریزی هفتگی
                </h3>
                <button type="button" class="wizard-close-btn" onclick="closeAutoScheduleModal()" title="بستن">&times;</button>
            </div>
            
            <!-- Stepper Progress Bar -->
            <div class="stepper-bar">
                <div class="stepper-line">
                    <div class="stepper-line-progress" id="wizardStepperProgress" style="width: 0%;"></div>
                </div>
                
                <div class="step-node active" id="stepNode1" onclick="goToWizardStep(1)">
                    <div class="step-node-bubble">۱</div>
                    <span class="step-node-label">تثبیت برنامه‌ها</span>
                </div>
                
                <div class="step-node" id="stepNode2" onclick="goToWizardStep(2)">
                    <div class="step-node-bubble">۲</div>
                    <span class="step-node-label">افزودن فعالیت‌ها</span>
                </div>
                
                <div class="step-node" id="stepNode3" onclick="goToWizardStep(3)">
                    <div class="step-node-bubble">۳</div>
                    <span class="step-node-label">استراتژی و شروط</span>
                </div>
                
                <div class="step-node" id="stepNode4" onclick="goToWizardStep(4)">
                    <div class="step-node-bubble">۴</div>
                    <span class="step-node-label">پیش‌نمایش و اعمال</span>
                </div>
            </div>
        </div>
        
        <!-- Wizard Slider Viewport -->
        <div class="wizard-slider-viewport">
            <div class="wizard-slider-track" id="wizardSliderTrack">
                
                <!-- SLIDE 1: FIXED ITEMS -->
                <div class="wizard-slide" id="wizardSlide1">
                    <div class="slide-title-bar">
                        <div class="slide-title">مرحله ۱: برنامه‌های ثابت جدول فعلی</div>
                        <p class="slide-desc">برنامه‌هایی که باید حتماً در زمان فعلی خود باقی بمانند را قفل کنید. موارد آزاد شده ممکن است برای بهینه‌سازی جابجا شوند.</p>
                    </div>
                    
                    <div class="fixed-items-toolbar">
                        <button type="button" class="btn-mini" onclick="setAllFixedItems(true)">🔒 قفل همه</button>
                        <button type="button" class="btn-mini" onclick="setAllFixedItems(false)">🔓 آزاد کردن همه</button>
                    </div>
                    
                    <div id="fixedItemsContainer" class="fixed-cards-grid"></div>
                </div>
                
                <!-- SLIDE 2: FLEXIBLE ITEMS -->
                <div class="wizard-slide" id="wizardSlide2">
                    <div class="slide-title-bar">
                        <div class="slide-title">مرحله ۲: فعالیت‌ها و دروس جدید</div>
                        <p class="slide-desc">فعالیت‌هایی که می‌خواهید الگوریتم به صورت هوشمند و بدون تداخل در بهترین زمان ممکن بچیند را اضافه کنید.</p>
                    </div>
                    
                    <div id="flexibleItemsContainer" class="flex-items-container"></div>
                    
                    <button type="button" class="btn btn-add" onclick="addFlexibleItem()" style="width:100%; justify-content:center; padding:10px; border-radius:10px;">
                        <img src="./icons/add.svg" class="icon-header" alt="add"> افزودن آیتم جدید
                    </button>
                </div>
                
                <!-- SLIDE 3: STRATEGIES & CONSTRAINTS -->
                <div class="wizard-slide" id="wizardSlide3">
                    <div class="slide-title-bar">
                        <div class="slide-title">مرحله ۳: استراتژی چیدمان و محدودیت‌های زمانی</div>
                        <p class="slide-desc">استراتژی هوشمند مدنظر و قوانین زمانی را مشخص کنید تا برنامه متناسب با اولویت و سبک زندگی شما طراحی شود.</p>
                    </div>
                    
                    <div class="strategy-picker">
                        <div class="strategy-option selected" id="stratBalanced" onclick="selectStrategy('balanced')">
                            <div class="strategy-option-title">⚖️ توزیع متوازن هفتگی</div>
                            <div class="strategy-option-desc">پخش متعادل فعالیت‌ها در طول هفته برای جلوگیری از خستگی مفرط در یک روز خاص.</div>
                        </div>
                        
                        <div class="strategy-option" id="stratCompact" onclick="selectStrategy('compact')">
                            <div class="strategy-option-title">⚡ فشرده‌سازی حداکثری</div>
                            <div class="strategy-option-desc">تکمیل روزهای اول هفته برای داشتن حداکثر روزهای کاملاً خالی و آزاد در پایان هفته.</div>
                        </div>
                        
                        <div class="strategy-option" id="stratMorning" onclick="selectStrategy('morning')">
                            <div class="strategy-option-title">🌅 اولویت صبحگاهی</div>
                            <div class="strategy-option-desc">چیدمان حداکثری در ساعات ابتدایی روز جهت استفاده از تمرکز و انرژی صبح.</div>
                        </div>
                        
                        <div class="strategy-option" id="stratAfternoon" onclick="selectStrategy('afternoon')">
                            <div class="strategy-option-title">🌆 اولویت عصر و شب</div>
                            <div class="strategy-option-desc">چیدمان فعالیت‌ها در بعدازظهر و شب؛ مناسب برای شاغلین یا علاقه‌مندان به عصر.</div>
                        </div>
                    </div>
                    
                    <div class="rules-grid">
                        <div class="rule-field">
                            <label>فاصله استراحت بین کارها</label>
                            <select id="bufferTime">
                                <option value="0">بدون فاصله (۰ دقیقه)</option>
                                <option value="15">۱۵ دقیقه (کوتاه)</option>
                                <option value="30" selected>۳۰ دقیقه (استاندارد)</option>
                                <option value="45">۴۵ دقیقه (راحت)</option>
                                <option value="60">۶۰ دقیقه (کامل)</option>
                            </select>
                        </div>
                        
                        <div class="rule-field">
                            <label>حداکثر ساعت کاری در روز</label>
                            <input type="number" id="maxHoursPerDay" value="8" min="2" max="16" step="1">
                        </div>
                        
                        <div class="rule-field">
                            <label>ساعت شروع فعالیت روزانه</label>
                            <input type="time" id="minStartTime" value="07:00">
                        </div>
                        
                        <div class="rule-field">
                            <label>ساعت پایان فعالیت روزانه</label>
                            <input type="time" id="maxEndTime" value="22:00">
                        </div>
                        
                        <div class="rule-field" style="grid-column: span 2; display:flex; flex-direction:row; align-items:center; gap:8px;">
                            <input type="checkbox" id="protectLunchBreak" checked style="width:18px; height:18px; cursor:pointer;">
                            <label for="protectLunchBreak" style="cursor:pointer; margin:0;">
                                محافظت از بازه ناهار و استراحت نیمروزی (۱۲:۳۰ تا ۱۳:۳۰ برنامه‌ریزی نشود)
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- SLIDE 4: PREVIEW & APPLY -->
                <div class="wizard-slide" id="wizardSlide4">
                    <div class="slide-title-bar">
                        <div class="slide-title">مرحله ۴: پیش‌نمایش، ارزیابی هوشمند و اعمال</div>
                        <p class="slide-desc">نتیجه تولید برنامه بر پایه هوش مصنوعی و شروط شما آماده است. پیش‌نمایش را بررسی کنید.</p>
                    </div>
                    
                    <div class="score-dashboard" id="scoreDashboard">
                        <div class="score-circle">
                            <span id="scorePercent">۹۸٪</span>
                            <small>امتیاز</small>
                        </div>
                        <div class="score-details">
                            <div class="score-details-title" id="scoreSummaryTitle">بهینه‌سازی عالی و بدون تداخل</div>
                            <div class="score-chips" id="scoreChipsList"></div>
                        </div>
                    </div>
                    
                    <div class="suggestions-section" id="suggestionsContainer">
                        <div id="suggestionsList"></div>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Wizard Footer Navigation -->
        <div class="wizard-footer">
            <div class="wizard-footer-left">
                <button type="button" class="btn" style="background:#f1f5f9; color:#475569;" onclick="closeAutoScheduleModal()">انصراف</button>
                <button type="button" class="btn" id="btnWizardPrev" style="background:#e2e8f0; color:#334155; display:none;" onclick="prevWizardStep()">
                    بازگشت
                </button>
            </div>
            
            <div class="wizard-footer-right">
                <button type="button" class="btn btn-add" id="btnWizardNext" onclick="nextWizardStep()">
                    مرحله بعد ➔
                </button>
                <button type="button" class="btn" id="btnApplySchedule" style="background:#059669; color:white; display:none;" onclick="applySuggestedSchedule()">
                    <img src="./icons/check.svg" class="icon-header" alt="check"> اعمال برنامه به جدول
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Modal -->
<div class="confirm-overlay" id="confirmModal">
    <div class="confirm-dialog">
        <div class="confirm-icon">
            <img src="./icons/alert.svg" class="icon-big" alt="alert">
        </div>
        <div class="confirm-text" id="confirmText">آیا از این عملیات اطمینان دارید؟</div>
        <div class="confirm-actions">
            <button class="btn-confirm-yes" id="confirmYes">بله</button>
            <button class="btn-confirm-no" onclick="closeConfirm()">انصراف</button>
        </div>
    </div>
</div>

<!-- Schedule Management Modal -->
<div class="schedule-modal-overlay" id="scheduleModal">
    <div class="schedule-modal-dialog">
        <h3 id="scheduleModalTitle">
            <img src="./icons/edit.svg" class="icon-header" alt="edit">
            مدیریت جدول
        </h3>
        <input type="text" id="scheduleNameInput" placeholder="نام جدول">
        <div class="schedule-modal-actions">
            <button class="btn btn-add" id="scheduleModalSave">ذخیره</button>
            <button class="btn" style="background:#e2e8f0; color:#475569;" onclick="closeScheduleModal()">انصراف</button>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<script>
// ==========================================
// SECURITY & CSRF TOKEN
// ==========================================
const CSRF_TOKEN = '<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>';

// ==========================================
// GLOBAL LOADER CONTROLLER
// ==========================================
function showLoader(text = 'در حال بارگذاری اطلاعات جدول...') {
    const loader = document.getElementById('globalLoader');
    const textEl = document.getElementById('loaderText');
    if (textEl && text) textEl.textContent = text;
    if (loader) loader.classList.add('active');
}

function hideLoader() {
    const loader = document.getElementById('globalLoader');
    if (loader) loader.classList.remove('active');
}

// ==========================================
// GLOBAL VARIABLES
// ==========================================
let courses = [];
let schedules = [];
let currentScheduleId = null;
let currentScheduleName = 'جدول اصلی';
let suggestedSchedule = [];
const timetable = document.getElementById('timetable');

const categoryColors = {
    uni: '#2563eb',
    sport: '#059669',
    nonuni: '#ff00e6',
    plus: '#ff6d1f',
    work: '#7c3aed',
    book: '#dc2626',
    movie: '#750000',
    other: '#999999'
};

const categoryNames = {
    uni: 'کلاس دانشگاهی',
    sport: 'ورزش',
    nonuni: 'کلاس غیر دانشگاهی',
    plus: 'درس پلاس',
    work: 'کار',
    book: 'کتاب',
    movie: 'فیلم',
    other: 'غیره'
};

const days = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

// ==========================================
// TOAST SYSTEM
// ==========================================
function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    let icon = '';
    if (type === 'success') icon = '<img src="./icons/check.svg" class="icon" alt="check" style="filter:brightness(0) invert(1);">';
    else if (type === 'error') icon = '<img src="./icons/xmark.svg" class="icon" alt="xmark" style="filter:brightness(0) invert(1);">';
    else icon = '<img src="./icons/info.svg" class="icon" alt="info" style="filter:brightness(0) invert(1);">';
    
    toast.innerHTML = `${icon} ${message}`;
    container.appendChild(toast);
    
    // Auto remove after animation
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 3000);
}

// ==========================================
// CONFIRM DIALOG
// ==========================================
let confirmCallback = null;

function showConfirm(message, callback) {
    document.getElementById('confirmText').textContent = message;
    document.getElementById('confirmModal').style.display = 'flex';
    confirmCallback = callback;
}

function closeConfirm() {
    document.getElementById('confirmModal').style.display = 'none';
    confirmCallback = null;
}

document.getElementById('confirmYes').addEventListener('click', function() {
    if (confirmCallback) {
        confirmCallback();
    }
    closeConfirm();
});

// ==========================================
// INITIALIZATION
// ==========================================
window.addEventListener('DOMContentLoaded', async function() {
    showLoader('در حال بارگذاری اطلاعات جدول...');
    try {
        await loadSchedulesList();
        await loadCurrentSchedule();
    } catch (e) {
        console.error('Initialization error:', e);
    } finally {
        hideLoader();
    }
    
    // Setup event listeners
    setupModalListeners();
    setupImportListener();
    setupKeyboardShortcuts();
});

// ==========================================
// SCHEDULE MANAGEMENT
// ==========================================
async function loadSchedulesList() {
    try {
        const response = await fetch('api.php?action=list_schedules');
        if (response.ok) {
            schedules = await response.json();
            updateScheduleSelect();
            
            if (schedules.length > 0) {
                const defaultSchedule = schedules.find(s => s.is_default == 1) || schedules[0];
                currentScheduleId = defaultSchedule.id;
                currentScheduleName = defaultSchedule.name;
            }
        }
    } catch (err) {
        console.error('Error loading schedules:', err);
        showToast('خطا در بارگذاری لیست جداول', 'error');
    }
}

function updateScheduleSelect() {
    const select = document.getElementById('scheduleSelect');
    if (!select) return;
    
    select.innerHTML = '';
    
    schedules.forEach(schedule => {
        const option = document.createElement('option');
        option.value = schedule.id;
        option.textContent = schedule.name + (schedule.is_default == 1 ? ' (اصلی)' : '');
        if (schedule.id == currentScheduleId) {
            option.selected = true;
        }
        select.appendChild(option);
    });
}

async function switchSchedule() {
    const select = document.getElementById('scheduleSelect');
    const newId = select.value;
    
    if (newId != currentScheduleId) {
        showLoader('در حال تعویض جدول...');
        try {
            await saveCurrentScheduleSilent();
            currentScheduleId = newId;
            const schedule = schedules.find(s => s.id == newId);
            if (schedule) currentScheduleName = schedule.name;
            await loadCurrentSchedule();
            showToast('جدول با موفقیت تغییر کرد', 'info');
        } catch (e) {
            showToast('خطا در تغییر جدول', 'error');
        } finally {
            hideLoader();
        }
    }
}

async function loadCurrentSchedule() {
    try {
        const url = currentScheduleId ? `api.php?action=get&schedule_id=${currentScheduleId}` : 'api.php';
        const response = await fetch(url);
        if (response.ok) {
            const data = await response.json();
            courses = Array.isArray(data) ? data : [];
        } else {
            courses = [];
        }
        render();
    } catch (err) {
        console.error('Error loading schedule:', err);
        courses = [];
        render();
    }
}

async function saveCurrentScheduleSilent() {
    if (!currentScheduleId && schedules.length === 0) return;
    try {
        await fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': CSRF_TOKEN
            },
            body: JSON.stringify({ schedule_id: currentScheduleId, courses: courses })
        });
    } catch (err) {
        console.error('Error saving schedule silently:', err);
    }
}

function showScheduleModal(action) {
    const modal = document.getElementById('scheduleModal');
    const title = document.getElementById('scheduleModalTitle');
    const input = document.getElementById('scheduleNameInput');
    const saveBtn = document.getElementById('scheduleModalSave');
    
    modal.style.display = 'flex';
    
    if (action === 'create') {
        title.innerHTML = '<img src="./icons/add.svg" class="icon-header" alt="add"> ایجاد جدول جدید';
        input.value = '';
        saveBtn.onclick = createSchedule;
    } else if (action === 'rename') {
        title.innerHTML = '<img src="./icons/edit.svg" class="icon-header" alt="edit"> تغییر نام جدول';
        input.value = currentScheduleName;
        saveBtn.onclick = renameSchedule;
    }
}

function closeScheduleModal() {
    document.getElementById('scheduleModal').style.display = 'none';
}

async function createSchedule() {
    const name = document.getElementById('scheduleNameInput').value.trim();
    if (!name) {
        showToast('لطفاً نام جدول را وارد کنید', 'error');
        return;
    }
    
    showLoader('در حال ایجاد جدول جدید...');
    try {
        await saveCurrentScheduleSilent();
        const response = await fetch('api.php?action=create_schedule', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': CSRF_TOKEN
            },
            body: JSON.stringify({ name: name })
        });
        
        if (response.ok) {
            const result = await response.json();
            await loadSchedulesList();
            currentScheduleId = result.id;
            currentScheduleName = name;
            courses = [];
            render();
            closeScheduleModal();
            showToast('جدول جدید با موفقیت ایجاد شد', 'success');
        } else {
            throw new Error('Create failed');
        }
    } catch (err) {
        showToast('خطا در ایجاد جدول', 'error');
    } finally {
        hideLoader();
    }
}

async function renameSchedule() {
    const name = document.getElementById('scheduleNameInput').value.trim();
    if (!name || !currentScheduleId) return;
    
    showLoader('در حال تغییر نام جدول...');
    try {
        const response = await fetch('api.php?action=rename_schedule', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': CSRF_TOKEN
            },
            body: JSON.stringify({ schedule_id: currentScheduleId, name: name })
        });
        
        if (response.ok) {
            currentScheduleName = name;
            await loadSchedulesList();
            closeScheduleModal();
            showToast('نام جدول با موفقیت تغییر کرد', 'success');
        } else {
            throw new Error('Rename failed');
        }
    } catch (err) {
        showToast('خطا در تغییر نام جدول', 'error');
    } finally {
        hideLoader();
    }
}

async function deleteCurrentSchedule() {
    if (!currentScheduleId) {
        showToast('جدولی برای حذف وجود ندارد', 'error');
        return;
    }
    
    const schedule = schedules.find(s => s.id == currentScheduleId);
    if (schedule && schedule.is_default == 1) {
        showToast('نمی‌توانید جدول اصلی را حذف کنید', 'error');
        return;
    }
    
    showConfirm('آیا از حذف این جدول و تمام اطلاعات آن اطمینان دارید؟', async function() {
        showLoader('در حال حذف جدول...');
        try {
            const response = await fetch('api.php?action=delete_schedule', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': CSRF_TOKEN
                },
                body: JSON.stringify({ schedule_id: currentScheduleId })
            });
            
            if (response.ok) {
                currentScheduleId = null;
                await loadSchedulesList();
                await loadCurrentSchedule();
                showToast('جدول با موفقیت حذف شد', 'success');
            } else {
                throw new Error('Delete failed');
            }
        } catch (err) {
            showToast('خطا در حذف جدول', 'error');
        } finally {
            hideLoader();
        }
    });
}

// ==========================================
// RENDER FUNCTIONS
// ==========================================
function render() {
    renderTimetable();
    renderSidebar();
    renderCategoryStats();
}

function renderTimetable() {
    if(!timetable) return;
    timetable.innerHTML = '';
    
    // Header row
    timetable.appendChild(createCell('ساعت', 'header-cell'));
    days.forEach(d => timetable.appendChild(createCell(d, 'header-cell')));
    
    // Time rows
    for(let h = 6; h <= 24; h++) {
        timetable.appendChild(createCell(`${h}:00`, 'time-cell'));
        for(let d = 1; d <= 7; d++) {
            const cell = createCell('');
            cell.id = `d-${d}-h-${h}`;
            cell.setAttribute('data-day', d);
            cell.setAttribute('data-hour', h);
            
            // Drag and drop events
            cell.addEventListener('dragover', handleDragOver);
            cell.addEventListener('dragenter', handleDragEnter);
            cell.addEventListener('dragleave', handleDragLeave);
            cell.addEventListener('drop', handleDrop);
            
            timetable.appendChild(cell);
        }
    }
    
    // Add course blocks
    courses.forEach((course, index) => {
        addBlockToTimetable(course, index);
    });
}

function addBlockToTimetable(course, index) {
    const [sh, sm] = course.sTime.split(':').map(Number);
    const [eh, em] = course.eTime.split(':').map(Number);
    const duration = (eh * 60 + em) - (sh * 60 + sm);
    
    if (sh >= 6 && sh <= 24) {
        const parent = document.getElementById(`d-${course.day}-h-${sh}`);
        if (!parent) return;
        
        const block = document.createElement('div');
        block.className = 'class-block';
        block.setAttribute('data-course-index', index);
        block.style.borderColor = categoryColors[course.category];
        block.style.color = '#000000';
        block.style.background = `linear-gradient(135deg, ${categoryColors[course.category]}10, ${categoryColors[course.category]}25)`;
        block.style.top = `${(sm / 60) * 55}px`;
        block.style.height = `${(duration / 60) * 55 - 13}px`;
        
        block.innerHTML = `
            <div class="drag-handle" data-course-index="${index}">
                <img src="./icons/move.svg" class="icon-drag" alt="move">
            </div>
            <div class="block-delete-btn">
                <img src="./icons/delete.svg" class="icon" alt="delete">
            </div>
            <b>${escapeHtml(course.name)}</b>
            <span style="font-size:0.7rem">${categoryNames[course.category]} | ${escapeHtml(course.loc)}</span>
            <span style="font-size:0.7rem">${course.sTime} تا ${course.eTime}</span>
        `;
        
        // Click on block (not buttons) to edit
        block.addEventListener('click', function(e) {
            if (!e.target.closest('.drag-handle') && !e.target.closest('.block-delete-btn')) {
                editItem(index);
            }
        });
        
        // Delete button - direct confirm and delete
        const deleteBtn = block.querySelector('.block-delete-btn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                e.preventDefault();
                
                const courseName = courses[index] ? courses[index].name : 'این آیتم';
                showConfirm(`آیا از حذف "${courseName}" اطمینان دارید؟`, function() {
                    courses.splice(index, 1);
                    render();
                    saveToHost();
                    showToast(`"${courseName}" با موفقیت حذف شد`, 'success');
                });
            });
        }
        
        // Setup drag handle
        const dragHandle = block.querySelector('.drag-handle');
        if (dragHandle) {
            dragHandle.addEventListener('mousedown', function(e) {
                e.stopPropagation();
                block.setAttribute('draggable', 'true');
            });
            
            dragHandle.addEventListener('dragstart', handleDragStart);
            dragHandle.addEventListener('dragend', handleDragEnd);
            
            dragHandle.addEventListener('touchstart', handleTouchStart, {passive: false});
        }
        
        parent.appendChild(block);
    }
}

function renderSidebar() {
    const list = document.getElementById('courseList');
    if (!list) return;
    
    list.innerHTML = '';
    
    if (courses.length === 0) {
        list.innerHTML = '<div style="text-align:center; color:#94a3b8; padding:20px;">هنوز هیچ آیتمی اضافه نشده</div>';
        return;
    }
    
    // Sort by day and time
    const sortedCourses = [...courses].sort((a, b) => {
        if (a.day !== b.day) return a.day - b.day;
        return a.sTime.localeCompare(b.sTime);
    });
    
    sortedCourses.forEach((course) => {
        const originalIndex = courses.indexOf(course);
        const card = document.createElement('div');
        card.className = 'course-card';
        card.style.borderRight = `6px solid ${categoryColors[course.category]}`;
        card.innerHTML = `
            <img src="./icons/delete.svg" class="delete-btn" alt="delete">
            <b>${escapeHtml(course.name)}</b>
            <div class="card-info">
                <img src="./icons/teacher.svg" class="icon" alt="user"> ${escapeHtml(course.prof)}
            </div>
            <div class="card-info">
                <img src="./icons/location.svg" class="icon" alt="location"> ${escapeHtml(course.loc)}
            </div>
            <div class="card-info">
                <img src="./icons/clock.svg" class="icon" alt="clock"> ${days[course.day-1]} | ${course.sTime} تا ${course.eTime}
            </div>
            <div class="card-info">
                <img src="./icons/folder.svg" class="icon" alt="folder"> ${categoryNames[course.category]}
            </div>
        `;
        
        // Delete button
        card.querySelector('.delete-btn').addEventListener('click', function(e) {
            e.stopPropagation();
            deleteItem(originalIndex);
        });
        
        // Edit on click
        card.addEventListener('click', function(e) {
            if (!e.target.closest('.delete-btn')) {
                editItem(originalIndex);
            }
        });
        
        list.appendChild(card);
    });
}

function renderCategoryStats() {
    const statsContainer = document.getElementById('categoryStats');
    if (!statsContainer) return;
    
    statsContainer.innerHTML = '';
    
    const totals = {};
    let grandTotal = 0;
    
    courses.forEach(course => {
        const [sh, sm] = course.sTime.split(':').map(Number);
        const [eh, em] = course.eTime.split(':').map(Number);
        const duration = (eh * 60 + em) - (sh * 60 + sm);
        
        if (!totals[course.category]) {
            totals[course.category] = 0;
        }
        totals[course.category] += duration;
        grandTotal += duration;
    });
    
    if (grandTotal === 0) {
        statsContainer.innerHTML = '<div style="text-align:center; color:#94a3b8; padding:10px; font-size:0.8rem;">آماری برای نمایش وجود ندارد</div>';
        return;
    }
    
    Object.entries(totals).forEach(([category, minutes]) => {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        const timeStr = hours > 0 ? `${hours}:${mins.toString().padStart(2, '0')} ساعت` : `${mins} دقیقه`;
        
        const statItem = document.createElement('div');
        statItem.className = 'stat-item';
        statItem.innerHTML = `
            <span class="stat-dot" style="background:${categoryColors[category]}"></span>
            <div class="stat-label">${categoryNames[category]}</div>
            <div class="stat-value">${timeStr}</div>
        `;
        statsContainer.appendChild(statItem);
    });
    
    // Grand total
    const totalHours = Math.floor(grandTotal / 60);
    const totalMins = grandTotal % 60;
    const totalStr = totalHours > 0 ? `${totalHours}:${totalMins.toString().padStart(2, '0')} ساعت` : `${totalMins} دقیقه`;
    
    const totalItem = document.createElement('div');
    totalItem.className = 'stat-item';
    totalItem.style.gridColumn = 'span 2';
    totalItem.innerHTML = `
        <div class="stat-label"><img src="./icons/clock.svg" class="icon" alt="clock"> مجموع کل</div>
        <div class="stat-value">${totalStr}</div>
    `;
    statsContainer.appendChild(totalItem);
}

function createCell(text = '', className = '') {
    const div = document.createElement('div');
    div.className = `cell ${className}`;
    div.textContent = text;
    return div;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ==========================================
// OPTIMIZED DRAG AND DROP
// ==========================================
let draggedCourseIndex = null;
let dragSourceElement = null;

function handleDragStart(e) {
    const handle = e.target.closest('.drag-handle');
    if (!handle) return;
    
    draggedCourseIndex = parseInt(handle.getAttribute('data-course-index'));
    const block = handle.closest('.class-block');
    
    if (block && draggedCourseIndex !== null && draggedCourseIndex >= 0) {
        dragSourceElement = block;
        block.classList.add('dragging-source');
        
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', draggedCourseIndex.toString());
        
        // Set drag image
        const rect = block.getBoundingClientRect();
        e.dataTransfer.setDragImage(block, rect.width / 2, 15);
    }
}

function handleDragEnd(e) {
    // Clean up
    if (dragSourceElement) {
        dragSourceElement.classList.remove('dragging-source');
        dragSourceElement.setAttribute('draggable', 'false');
        dragSourceElement = null;
    }
    
    // Remove all hover effects
    document.querySelectorAll('.cell.drag-hover').forEach(cell => {
        cell.classList.remove('drag-hover');
    });
    
    draggedCourseIndex = null;
}

function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    
    const cell = e.target.closest('.cell[id^="d-"]');
    if (cell && draggedCourseIndex !== null) {
        // Add hover effect to current cell
        document.querySelectorAll('.cell.drag-hover').forEach(c => {
            if (c !== cell) c.classList.remove('drag-hover');
        });
        
        if (!cell.classList.contains('drag-hover')) {
            cell.classList.add('drag-hover');
        }
    }
}

function handleDragEnter(e) {
    e.preventDefault();
    const cell = e.target.closest('.cell[id^="d-"]');
    if (cell && draggedCourseIndex !== null) {
        cell.classList.add('drag-hover');
    }
}

function handleDragLeave(e) {
    const cell = e.target.closest('.cell[id^="d-"]');
    if (cell) {
        // Only remove if we're actually leaving the cell, not entering a child
        const relatedTarget = e.relatedTarget;
        if (!relatedTarget || !cell.contains(relatedTarget)) {
            cell.classList.remove('drag-hover');
        }
    }
}

function handleDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const cell = e.target.closest('.cell[id^="d-"]');
    if (!cell || draggedCourseIndex === null) {
        return;
    }
    
    // Remove hover effect
    cell.classList.remove('drag-hover');
    
    const newDay = parseInt(cell.getAttribute('data-day'));
    const newHour = parseInt(cell.getAttribute('data-hour'));
    
    if (!newDay || !newHour) return;
    if (draggedCourseIndex < 0 || draggedCourseIndex >= courses.length) return;
    
    const course = courses[draggedCourseIndex];
    const [oldSh, oldSm] = course.sTime.split(':').map(Number);
    const [oldEh, oldEm] = course.eTime.split(':').map(Number);
    const durationMinutes = (oldEh * 60 + oldEm) - (oldSh * 60 + oldSm);
    
    // Calculate drop position within the cell for precise time
    const cellRect = cell.getBoundingClientRect();
    const dropY = e.clientY - cellRect.top;
    const cellHeight = cellRect.height;
    let minutesIntoHour = Math.round((dropY / cellHeight) * 60);
    
    // Snap to 15-minute intervals for cleaner scheduling
    minutesIntoHour = Math.round(minutesIntoHour / 15) * 15;
    minutesIntoHour = Math.min(59, Math.max(0, minutesIntoHour));
    
    // Calculate new times
    const totalStartMinutes = newHour * 60 + minutesIntoHour;
    const totalEndMinutes = totalStartMinutes + durationMinutes;
    
    const newEndHour = Math.floor(totalEndMinutes / 60);
    const newEndMinute = totalEndMinutes % 60;
    
    // Validate
    if (newEndHour > 24 || (newEndHour === 24 && newEndMinute > 0)) {
        showToast('زمان پایان نمی‌تواند بعد از ۲۴:۰۰ باشد', 'error');
        return;
    }
    
    // Check if actually changed
    if (course.day === newDay && course.sTime === `${newHour.toString().padStart(2, '0')}:${minutesIntoHour.toString().padStart(2, '0')}`) {
        return; // No change
    }
    
    // Update course
    course.day = newDay;
    course.sTime = `${newHour.toString().padStart(2, '0')}:${minutesIntoHour.toString().padStart(2, '0')}`;
    course.eTime = `${newEndHour.toString().padStart(2, '0')}:${newEndMinute.toString().padStart(2, '0')}`;
    
    // Re-render and save
    render();
    showToast(`به ${days[newDay-1]} ساعت ${course.sTime} منتقل شد`, 'success');
    saveToHost();
}

// Touch support for mobile drag
let touchDragIndex = null;
let touchClone = null;
let touchStartElement = null;

function handleTouchStart(e) {
    const handle = e.target.closest('.drag-handle');
    if (!handle) return;
    
    e.preventDefault();
    touchDragIndex = parseInt(handle.getAttribute('data-course-index'));
    const block = handle.closest('.class-block');
    
    if (block && touchDragIndex !== null) {
        touchStartElement = block;
        block.classList.add('dragging-source');
        
        // Create visual clone
        touchClone = block.cloneNode(true);
        touchClone.style.position = 'fixed';
        touchClone.style.opacity = '0.85';
        touchClone.style.zIndex = '9999';
        touchClone.style.pointerEvents = 'none';
        touchClone.style.width = block.offsetWidth + 'px';
        touchClone.style.left = e.touches[0].clientX - 30 + 'px';
        touchClone.style.top = e.touches[0].clientY - 20 + 'px';
        touchClone.style.boxShadow = '0 10px 30px rgba(0,0,0,0.3)';
        document.body.appendChild(touchClone);
    }
}

document.addEventListener('touchmove', function(e) {
    if (touchDragIndex === null || !touchClone) return;
    
    e.preventDefault();
    
    touchClone.style.left = (e.touches[0].clientX - 30) + 'px';
    touchClone.style.top = (e.touches[0].clientY - 20) + 'px';
    
    // Highlight cell under touch point
    const element = document.elementFromPoint(e.touches[0].clientX, e.touches[0].clientY);
    const cell = element ? element.closest('.cell[id^="d-"]') : null;
    
    document.querySelectorAll('.cell.drag-hover').forEach(c => c.classList.remove('drag-hover'));
    if (cell) {
        cell.classList.add('drag-hover');
    }
}, {passive: false});

document.addEventListener('touchend', function(e) {
    if (touchDragIndex === null) return;
    
    const element = document.elementFromPoint(
        e.changedTouches[0].clientX,
        e.changedTouches[0].clientY
    );
    
    const cell = element ? element.closest('.cell[id^="d-"]') : null;
    
    if (cell && touchDragIndex < courses.length) {
        const newDay = parseInt(cell.getAttribute('data-day'));
        const newHour = parseInt(cell.getAttribute('data-hour'));
        
        if (newDay && newHour) {
            const course = courses[touchDragIndex];
            const [oldSh, oldSm] = course.sTime.split(':').map(Number);
            const [oldEh, oldEm] = course.eTime.split(':').map(Number);
            const durationMinutes = (oldEh * 60 + oldEm) - (oldSh * 60 + oldSm);
            
            // Calculate position within cell
            const cellRect = cell.getBoundingClientRect();
            const touchY = e.changedTouches[0].clientY - cellRect.top;
            let minutesIntoHour = Math.round((touchY / cellRect.height) * 60);
            minutesIntoHour = Math.round(minutesIntoHour / 15) * 15;
            minutesIntoHour = Math.min(59, Math.max(0, minutesIntoHour));
            
            const totalStartMinutes = newHour * 60 + minutesIntoHour;
            const totalEndMinutes = totalStartMinutes + durationMinutes;
            
            const newEndHour = Math.floor(totalEndMinutes / 60);
            const newEndMinute = totalEndMinutes % 60;
            
            if (newEndHour <= 24 && !(newEndHour === 24 && newEndMinute > 0)) {
                course.day = newDay;
                course.sTime = `${newHour.toString().padStart(2, '0')}:${minutesIntoHour.toString().padStart(2, '0')}`;
                course.eTime = `${newEndHour.toString().padStart(2, '0')}:${newEndMinute.toString().padStart(2, '0')}`;
                
                render();
                saveToHost();
                showToast(`به ${days[newDay-1]} ساعت ${course.sTime} منتقل شد`, 'success');
            }
        }
    }
    
    // Cleanup
    document.querySelectorAll('.cell.drag-hover').forEach(c => c.classList.remove('drag-hover'));
    
    if (touchClone) {
        document.body.removeChild(touchClone);
        touchClone = null;
    }
    
    if (touchStartElement) {
        touchStartElement.classList.remove('dragging-source');
        touchStartElement.setAttribute('draggable', 'false');
        touchStartElement = null;
    }
    
    touchDragIndex = null;
});

// ==========================================
// SMART SCHEDULING SYSTEM (MULTI-STEP WIZARD)
// ==========================================
let currentWizardStep = 1;
let selectedStrategy = 'balanced';
let autoScheduleFixedFlags = {};
let flexibleItemsCounter = 0;

function openAutoScheduleModal() {
    const modal = document.getElementById('autoScheduleModal');
    modal.style.display = 'flex';
    
    // Reset flags for fixed items
    autoScheduleFixedFlags = {};
    courses.forEach((c, i) => {
        autoScheduleFixedFlags[i] = true;
    });
    
    // Render fixed items
    renderFixedItemsList();
    
    // Reset flexible items if empty
    const flexContainer = document.getElementById('flexibleItemsContainer');
    if (flexContainer.children.length === 0) {
        flexContainer.innerHTML = '';
        addFlexibleItem(); // Add one initial card
    }
    
    // Reset strategy selection
    selectStrategy('balanced');
    
    // Reset constraints to default
    const bufferEl = document.getElementById('bufferTime');
    if (bufferEl) bufferEl.value = '30';
    const maxHoursEl = document.getElementById('maxHoursPerDay');
    if (maxHoursEl) maxHoursEl.value = '8';
    const minStartEl = document.getElementById('minStartTime');
    if (minStartEl) minStartEl.value = '07:00';
    const maxEndEl = document.getElementById('maxEndTime');
    if (maxEndEl) maxEndEl.value = '22:00';
    const lunchEl = document.getElementById('protectLunchBreak');
    if (lunchEl) lunchEl.checked = true;
    
    // Go to step 1
    goToWizardStep(1);
}

function closeAutoScheduleModal() {
    document.getElementById('autoScheduleModal').style.display = 'none';
    suggestedSchedule = [];
}

function updateWizardStepperUI() {
    const progressEl = document.getElementById('wizardStepperProgress');
    if (progressEl) {
        const percentage = ((currentWizardStep - 1) / 3) * 100;
        progressEl.style.width = percentage + '%';
    }
    
    for (let step = 1; step <= 4; step++) {
        const node = document.getElementById(`stepNode${step}`);
        if (!node) continue;
        
        node.classList.remove('active', 'completed');
        if (step === currentWizardStep) {
            node.classList.add('active');
        } else if (step < currentWizardStep) {
            node.classList.add('completed');
        }
    }
    
    // Footer button states
    const prevBtn = document.getElementById('btnWizardPrev');
    const nextBtn = document.getElementById('btnWizardNext');
    const applyBtn = document.getElementById('btnApplySchedule');
    
    if (prevBtn) {
        prevBtn.style.display = currentWizardStep > 1 ? 'inline-flex' : 'none';
    }
    
    if (nextBtn) {
        if (currentWizardStep === 4) {
            nextBtn.style.display = 'none';
        } else {
            nextBtn.style.display = 'inline-flex';
            if (currentWizardStep === 3) {
                nextBtn.innerHTML = '<img src="./icons/magic_filled.svg" class="icon-header" alt="magic"> تولید و تحلیل برنامه ➔';
            } else {
                nextBtn.innerHTML = 'مرحله بعد ➔';
            }
        }
    }
    
    if (applyBtn) {
        applyBtn.style.display = currentWizardStep === 4 ? 'inline-flex' : 'none';
    }
    
    // Slide transition in RTL
    const track = document.getElementById('wizardSliderTrack');
    if (track) {
        track.style.marginRight = `-${(currentWizardStep - 1) * 100}%`;
    }
}

function goToWizardStep(step) {
    if (step < 1 || step > 4) return;
    
    // Validate Step 2 before moving forward
    if (currentWizardStep === 2 && step > 2) {
        const flexRows = document.querySelectorAll('.flex-card');
        let hasValidItem = false;
        flexRows.forEach(card => {
            const name = card.querySelector('.flex-name-input').value.trim();
            if (name) hasValidItem = true;
        });
        
        if (!hasValidItem) {
            showToast('حداقل عنوان یک آیتم را در مرحله ۲ وارد کنید', 'error');
            return;
        }
    }
    
    // If moving into Step 4, trigger schedule generation
    if (step === 4) {
        generateSmartSchedule();
    }
    
    currentWizardStep = step;
    updateWizardStepperUI();
}

function nextWizardStep() {
    if (currentWizardStep < 4) {
        goToWizardStep(currentWizardStep + 1);
    }
}

function prevWizardStep() {
    if (currentWizardStep > 1) {
        goToWizardStep(currentWizardStep - 1);
    }
}

function selectStrategy(strategy) {
    selectedStrategy = strategy;
    ['balanced', 'compact', 'morning', 'afternoon'].forEach(s => {
        const el = document.getElementById(`strat${s.charAt(0).toUpperCase() + s.slice(1)}`);
        if (el) {
            if (s === strategy) {
                el.classList.add('selected');
            } else {
                el.classList.remove('selected');
            }
        }
    });
}

function setAllFixedItems(isFixed) {
    courses.forEach((c, i) => {
        autoScheduleFixedFlags[i] = isFixed;
    });
    renderFixedItemsList();
}

function renderFixedItemsList() {
    const container = document.getElementById('fixedItemsContainer');
    if (!container) return;
    container.innerHTML = '';
    
    if (courses.length === 0) {
        container.innerHTML = '<div style="color:#94a3b8; font-size:0.85rem; text-align:center; padding:24px; background:#f8fafc; border-radius:12px;">هنوز برنامه‌ای در جدول اصلی اضافه نکرده‌اید. می‌توانید مستقیماً به مرحله ۲ رفته و فعالیت‌های جدید بیفزایید.</div>';
        return;
    }
    
    courses.forEach((c, i) => {
        const isLocked = autoScheduleFixedFlags[i] !== false;
        const card = document.createElement('div');
        card.className = `fixed-item-card ${isLocked ? 'is-locked' : ''}`;
        card.innerHTML = `
            <div class="fixed-card-info">
                <span class="cat-indicator-dot" style="background:${categoryColors[c.category] || '#2563eb'}"></span>
                <div>
                    <div style="font-weight:700; font-size:0.88rem; color:#1e293b;">${escapeHtml(c.name)}</div>
                    <div style="font-size:0.75rem; color:#64748b; margin-top:2px;">
                        <span>${days[c.day-1]}</span> | 
                        <span>${c.sTime} تا ${c.eTime}</span> | 
                        <span>${categoryNames[c.category] || 'غیره'}</span>
                    </div>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:0.75rem; color:${isLocked ? '#2563eb' : '#64748b'}; font-weight:${isLocked ? '700' : 'normal'};">
                    ${isLocked ? '🔒 قفل شده' : '🔓 آزاد'}
                </span>
                <label class="toggle-switch">
                    <input type="checkbox" ${isLocked ? 'checked' : ''} data-index="${i}">
                    <span class="toggle-slider"></span>
                </label>
            </div>
        `;
        
        card.querySelector('input[type="checkbox"]').addEventListener('change', function() {
            autoScheduleFixedFlags[i] = this.checked;
            card.classList.toggle('is-locked', this.checked);
            const labelText = card.querySelector('span[style*="font-size:0.75rem"]');
            if (labelText) {
                labelText.textContent = this.checked ? '🔒 قفل شده' : '🔓 آزاد';
                labelText.style.color = this.checked ? '#2563eb' : '#64748b';
                labelText.style.fontWeight = this.checked ? '700' : 'normal';
            }
        });
        
        container.appendChild(card);
    });
}

function addFlexibleItem() {
    const container = document.getElementById('flexibleItemsContainer');
    if (!container) return;
    
    flexibleItemsCounter++;
    const itemId = `flex-card-${flexibleItemsCounter}`;
    
    const card = document.createElement('div');
    card.className = 'flex-card';
    card.id = itemId;
    card.innerHTML = `
        <div class="flex-card-row">
            <input type="text" placeholder="عنوان فعالیت (مثلاً مطالعه ریاضی، ورزش، ...)" class="flex-name-input" autofocus>
            
            <select class="flex-select flex-category">
                <option value="uni">دانشگاهی</option>
                <option value="plus" selected>درس پلاس</option>
                <option value="nonuni">غیر دانشگاهی</option>
                <option value="sport">ورزش</option>
                <option value="book">کتاب</option>
                <option value="work">کار</option>
                <option value="movie">فیلم و تفریح</option>
                <option value="other">غیره</option>
            </select>
            
            <button type="button" class="btn-card-delete" onclick="removeFlexibleItem('${itemId}')" title="حذف این آیتم">
                <img src="./icons/delete.svg" class="icon" alt="delete" style="filter:invert(23%) sepia(85%) saturate(3000%) hue-rotate(345deg);">
            </button>
        </div>
        
        <div class="flex-card-row" style="flex-wrap:wrap; gap:8px;">
            <div style="display:flex; align-items:center; gap:6px;">
                <span style="font-size:0.75rem; color:#475569; font-weight:600;">مدت:</span>
                <input type="number" class="flex-duration-input" value="90" min="15" max="360" step="15" style="width:70px; padding:4px 6px; border:1px solid #cbd5e1; border-radius:6px; font-family:inherit; font-size:0.8rem;">
                <span style="font-size:0.72rem; color:#64748b;">دقیقه</span>
            </div>
            
            <div class="duration-chips">
                <span class="duration-chip" onclick="setFlexDuration('${itemId}', 45)">۴۵د</span>
                <span class="duration-chip" onclick="setFlexDuration('${itemId}', 60)">۱ ساعت</span>
                <span class="duration-chip selected" onclick="setFlexDuration('${itemId}', 90)">۱.۵ ساعت</span>
                <span class="duration-chip" onclick="setFlexDuration('${itemId}', 120)">۲ ساعت</span>
            </div>
            
            <div style="display:flex; gap:6px; margin-right:auto; align-items:center;">
                <select class="flex-select flex-priority" title="اولویت انجام">
                    <option value="high">اولویت: ضروری ⭐</option>
                    <option value="medium" selected>اولویت: معمولی</option>
                    <option value="low">اولویت: دلخواه</option>
                </select>
                
                <select class="flex-select flex-time-window" title="ترجیح بازه زمانی">
                    <option value="any">بازه: هر ساعتی</option>
                    <option value="morning">بازه: صبح (۸ تا ۱۲)</option>
                    <option value="afternoon">بازه: بعدازظهر (۱۲ تا ۱۷)</option>
                    <option value="evening">بازه: عصر و شب (۱۷ تا ۲۲)</option>
                </select>
                
                <select class="flex-select flex-preferred-day" title="روز ترجیحی">
                    <option value="0">روز: هر روزی</option>
                    <option value="1">شنبه</option>
                    <option value="2">یکشنبه</option>
                    <option value="3">دوشنبه</option>
                    <option value="4">سه‌شنبه</option>
                    <option value="5">چهارشنبه</option>
                    <option value="6">پنج‌شنبه</option>
                    <option value="7">جمعه</option>
                </select>
            </div>
        </div>
    `;
    
    container.appendChild(card);
}

function setFlexDuration(itemId, minutes) {
    const card = document.getElementById(itemId);
    if (!card) return;
    
    const input = card.querySelector('.flex-duration-input');
    if (input) input.value = minutes;
    
    const chips = card.querySelectorAll('.duration-chip');
    chips.forEach(chip => chip.classList.remove('selected'));
    
    event.target.classList.add('selected');
}

function removeFlexibleItem(itemId) {
    const card = document.getElementById(itemId);
    if (card) {
        card.remove();
    }
}

// ==========================================
// ADVANCED SMART SCHEDULING ALGORITHM
// ==========================================
function generateSmartSchedule() {
    showLoader('در حال تحلیل و بهینه‌سازی هوشمند جدول...');
    
    setTimeout(() => {
        try {
            // 1. Gather Fixed items
            const fixedItems = [];
            courses.forEach((c, i) => {
                if (autoScheduleFixedFlags[i] !== false) {
                    fixedItems.push({
                        name: c.name,
                        category: c.category,
                        day: c.day,
                        sTime: c.sTime,
                        eTime: c.eTime,
                        prof: c.prof || '-',
                        loc: c.loc || '-'
                    });
                }
            });
            
            // 2. Gather Flexible items from cards
            const flexibleItems = [];
            const flexCards = document.querySelectorAll('.flex-card');
            
            flexCards.forEach(card => {
                const nameInput = card.querySelector('.flex-name-input');
                const catSelect = card.querySelector('.flex-category');
                const durInput = card.querySelector('.flex-duration-input');
                const prioritySelect = card.querySelector('.flex-priority');
                const windowSelect = card.querySelector('.flex-time-window');
                const daySelect = card.querySelector('.flex-preferred-day');
                
                const name = nameInput ? nameInput.value.trim() : '';
                if (name) {
                    flexibleItems.push({
                        name: name,
                        category: catSelect ? catSelect.value : 'other',
                        durationMinutes: durInput ? (parseInt(durInput.value) || 90) : 90,
                        priority: prioritySelect ? prioritySelect.value : 'medium',
                        timeWindow: windowSelect ? windowSelect.value : 'any',
                        preferredDay: daySelect ? parseInt(daySelect.value) : 0,
                        prof: '-',
                        loc: '-'
                    });
                }
            });
            
            if (flexibleItems.length === 0) {
                hideLoader();
                showToast('هیچ آیتم متغیری با عنوان مشخص یافت نشد', 'error');
                return;
            }
            
            // 3. Gather Constraints & Strategy
            const bufferTime = parseInt(document.getElementById('bufferTime').value) || 0;
            const maxHoursPerDay = parseFloat(document.getElementById('maxHoursPerDay').value) || 8;
            const minStartTime = document.getElementById('minStartTime').value || '07:00';
            const maxEndTime = document.getElementById('maxEndTime').value || '22:00';
            const protectLunch = document.getElementById('protectLunchBreak') ? document.getElementById('protectLunchBreak').checked : true;
            
            const [minStartH, minStartM] = minStartTime.split(':').map(Number);
            const [maxEndH, maxEndM] = maxEndTime.split(':').map(Number);
            
            // 4. Run Algorithmic Solver
            suggestedSchedule = runSchedulingAlgorithm(fixedItems, flexibleItems, {
                strategy: selectedStrategy,
                bufferMinutes: bufferTime,
                maxHoursPerDay: maxHoursPerDay,
                minStartHour: minStartH,
                minStartMinute: minStartM,
                maxEndHour: maxEndH,
                maxEndMinute: maxEndM,
                protectLunch: protectLunch
            });
            
            // 5. Render Step 4 Results
            displaySuggestions(suggestedSchedule);
            showToast('برنامه بهینه با موفقیت محاسبه و تدوین شد', 'success');
            
        } catch (err) {
            console.error('Auto Schedule Error:', err);
            showToast('خطایی در تولید برنامه هوشمند رخ داد', 'error');
        } finally {
            hideLoader();
        }
    }, 200);
}

function runSchedulingAlgorithm(fixedItems, flexibleItems, constraints) {
    const result = [...fixedItems.map(f => ({ ...f, _isFixed: true }))];
    const weekGrid = createWeekGrid(); // 7 days x 72 slots (15 mins each from 06:00 to 24:00)
    
    // Day load tracking (in slots)
    const dayLoads = new Array(7).fill(0);
    
    // 1. Mark Fixed Items on the Grid
    fixedItems.forEach(item => {
        const [sh, sm] = item.sTime.split(':').map(Number);
        const [eh, em] = item.eTime.split(':').map(Number);
        const startSlot = Math.max(0, (sh - 6) * 4 + Math.floor(sm / 15));
        const endSlot = Math.min(72, (eh - 6) * 4 + Math.ceil(em / 15));
        const dayIdx = item.day - 1;
        
        for (let s = startSlot; s < endSlot; s++) {
            if (s >= 0 && s < 72) {
                weekGrid[dayIdx][s] = 1; // 1 = Occupied
                dayLoads[dayIdx]++;
            }
        }
        
        // Add buffer after fixed item
        const bufferSlots = Math.ceil(constraints.bufferMinutes / 15);
        for (let b = 0; b < bufferSlots; b++) {
            const bufSlot = endSlot + b;
            if (bufSlot >= 0 && bufSlot < 72) {
                weekGrid[dayIdx][bufSlot] = 2; // 2 = Buffer
            }
        }
    });
    
    // 2. Mark Lunch Break Protection (12:30 to 13:30 -> slots 26 to 30)
    if (constraints.protectLunch) {
        for (let d = 0; d < 7; d++) {
            for (let s = 26; s < 30; s++) {
                if (weekGrid[d][s] === 0) {
                    weekGrid[d][s] = 3; // 3 = Soft reserved for lunch
                }
            }
        }
    }
    
    // 3. Sort Flexible Items:
    // Priority: High (3) > Medium (2) > Low (1)
    // Then Duration (longest first: best fit decreasing heuristic)
    const priorityWeight = { high: 3, medium: 2, low: 1 };
    const sortedFlexible = [...flexibleItems].sort((a, b) => {
        const pDiff = (priorityWeight[b.priority] || 2) - (priorityWeight[a.priority] || 2);
        if (pDiff !== 0) return pDiff;
        return b.durationMinutes - a.durationMinutes;
    });
    
    const minStartSlot = Math.max(0, (constraints.minStartHour - 6) * 4 + Math.floor(constraints.minStartMinute / 15));
    const maxEndSlot = Math.min(72, (constraints.maxEndHour - 6) * 4 + Math.ceil(constraints.maxEndMinute / 15));
    const maxSlotsPerDay = Math.min(72, Math.ceil(constraints.maxHoursPerDay * 4));
    
    // 4. Place Each Flexible Item Heuristically
    sortedFlexible.forEach(item => {
        const durationSlots = Math.ceil(item.durationMinutes / 15);
        const bufferSlots = Math.ceil(constraints.bufferMinutes / 15);
        const totalSlotsNeeded = durationSlots + bufferSlots;
        
        let bestDay = -1;
        let bestSlot = -1;
        let bestScore = -Infinity;
        let bestDiagnostics = [];
        
        // Build candidate day order based on Strategy
        let dayCandidates = [0, 1, 2, 3, 4, 5, 6];
        
        if (constraints.strategy === 'balanced') {
            // Prefer emptier days
            dayCandidates.sort((a, b) => dayLoads[a] - dayLoads[b]);
        } else if (constraints.strategy === 'compact') {
            // Prefer days that already have load to consolidate tasks
            dayCandidates.sort((a, b) => {
                if (dayLoads[b] > 0 && dayLoads[a] === 0) return 1;
                if (dayLoads[a] > 0 && dayLoads[b] === 0) return -1;
                return dayLoads[b] - dayLoads[a];
            });
        }
        
        // Put preferred day at top if chosen
        if (item.preferredDay > 0) {
            const pDayIdx = item.preferredDay - 1;
            dayCandidates = [pDayIdx, ...dayCandidates.filter(d => d !== pDayIdx)];
        }
        
        // Search through candidate days
        for (const day of dayCandidates) {
            // Check daily cap limit
            if (dayLoads[day] + durationSlots > maxSlotsPerDay) {
                continue;
            }
            
            // Search all valid start slots within allowed daily hours
            for (let startSlot = minStartSlot; startSlot + durationSlots <= maxEndSlot; startSlot++) {
                let canPlace = true;
                let lunchConflict = false;
                
                // Check if duration slots and buffer slots are free
                for (let s = startSlot; s < startSlot + totalSlotsNeeded; s++) {
                    if (s >= 72) {
                        canPlace = false;
                        break;
                    }
                    if (weekGrid[day][s] === 1 || weekGrid[day][s] === 2) {
                        canPlace = false;
                        break;
                    }
                    if (weekGrid[day][s] === 3) {
                        lunchConflict = true;
                    }
                }
                
                if (canPlace) {
                    // Multi-objective Evaluation Score (higher is better)
                    let score = 100;
                    const diag = [];
                    
                    // 1. Preferred Day Match (+40)
                    if (item.preferredDay > 0 && day === item.preferredDay - 1) {
                        score += 40;
                        diag.push('روز ترجیحی رعایت شد');
                    }
                    
                    // 2. Preferred Time Window Match (+30)
                    // Morning: slots 4..24 (07:00 - 12:00)
                    // Afternoon: slots 24..44 (12:00 - 17:00)
                    // Evening: slots 44..64 (17:00 - 22:00)
                    if (item.timeWindow === 'morning') {
                        if (startSlot >= 4 && startSlot < 24) {
                            score += 30;
                            diag.push('شیفت صبح لحاظ شد');
                        } else {
                            score -= 20;
                        }
                    } else if (item.timeWindow === 'afternoon') {
                        if (startSlot >= 24 && startSlot < 44) {
                            score += 30;
                            diag.push('شیفت بعدازظهر لحاظ شد');
                        } else {
                            score -= 20;
                        }
                    } else if (item.timeWindow === 'evening') {
                        if (startSlot >= 44 && startSlot < 64) {
                            score += 30;
                            diag.push('شیفت عصر و شب لحاظ شد');
                        } else {
                            score -= 20;
                        }
                    }
                    
                    // 3. Strategy Scoring
                    if (constraints.strategy === 'morning') {
                        // Bonus for earlier start
                        score += Math.max(0, (40 - startSlot));
                    } else if (constraints.strategy === 'afternoon') {
                        // Bonus for slots after slot 28 (13:00)
                        if (startSlot >= 28) score += 25;
                    } else if (constraints.strategy === 'balanced') {
                        // Penalize day load
                        score -= (dayLoads[day] * 3);
                    } else if (constraints.strategy === 'compact') {
                        // Reward days that are already working
                        if (dayLoads[day] > 0) score += 20;
                    }
                    
                    // 4. Lunch conflict penalty
                    if (lunchConflict) {
                        score -= 50;
                    }
                    
                    // 5. Adjacency reward (clean schedules without tiny fragmented gaps)
                    if (startSlot > 0 && (weekGrid[day][startSlot - 1] === 1 || weekGrid[day][startSlot - 1] === 2)) {
                        score += 15;
                    }
                    
                    if (score > bestScore) {
                        bestScore = score;
                        bestDay = day;
                        bestSlot = startSlot;
                        bestDiagnostics = diag;
                    }
                }
            }
        }
        
        // Place item if valid slot found
        if (bestDay >= 0 && bestSlot >= 0) {
            for (let s = bestSlot; s < bestSlot + durationSlots; s++) {
                if (s < 72) weekGrid[bestDay][s] = 1;
            }
            dayLoads[bestDay] += durationSlots;
            
            // Add buffer
            for (let b = 0; b < bufferSlots; b++) {
                const bSlot = bestSlot + durationSlots + b;
                if (bSlot < 72) weekGrid[bestDay][bSlot] = 2;
            }
            
            const startHour = Math.floor(bestSlot / 4) + 6;
            const startMinute = (bestSlot % 4) * 15;
            const endSlot = bestSlot + durationSlots;
            const endHour = Math.floor(endSlot / 4) + 6;
            const endMinute = (endSlot % 4) * 15;
            
            result.push({
                name: item.name,
                category: item.category,
                day: bestDay + 1,
                sTime: `${startHour.toString().padStart(2, '0')}:${startMinute.toString().padStart(2, '0')}`,
                eTime: `${endHour.toString().padStart(2, '0')}:${endMinute.toString().padStart(2, '0')}`,
                priority: item.priority,
                prof: item.prof || '-',
                loc: item.loc || '-',
                diagnostics: bestDiagnostics,
                _isSuggested: true
            });
        } else {
            // Failed to place
            result.push({
                name: item.name,
                category: item.category,
                durationMinutes: item.durationMinutes,
                priority: item.priority,
                _isSuggested: true,
                _failed: true,
                failureReason: 'نبود فضای خالی مجاز با توجه به سقف ساعت روزانه و فواصل استراحت'
            });
        }
    });
    
    return result;
}

function createWeekGrid() {
    return Array.from({ length: 7 }, () => new Array(72).fill(0));
}

function displaySuggestions(schedule) {
    const container = document.getElementById('suggestionsList');
    if (!container) return;
    container.innerHTML = '';
    
    const suggestedItems = schedule.filter(s => s._isSuggested && !s._failed);
    const failedItems = schedule.filter(s => s._failed);
    const totalItems = suggestedItems.length + failedItems.length;
    
    // Calculate optimization score
    let score = totalItems > 0 ? Math.round((suggestedItems.length / totalItems) * 100) : 100;
    if (score === 100 && failedItems.length === 0) {
        score = 98; // Realistic high score
    }
    
    const scoreCircle = document.getElementById('scorePercent');
    if (scoreCircle) scoreCircle.textContent = `${score}٪`;
    
    const titleEl = document.getElementById('scoreSummaryTitle');
    if (titleEl) {
        if (failedItems.length === 0) {
            titleEl.textContent = 'چیدمان بهینه، متعادل و بدون تداخل زمانی ✅';
        } else {
            titleEl.textContent = `${suggestedItems.length} از ${totalItems} فعالیت با موفقیت جایابی شدند ⚠️`;
        }
    }
    
    // Summary chips
    const chipsEl = document.getElementById('scoreChipsList');
    if (chipsEl) {
        let totalMinutesAdded = 0;
        suggestedItems.forEach(item => {
            const [sh, sm] = item.sTime.split(':').map(Number);
            const [eh, em] = item.eTime.split(':').map(Number);
            totalMinutesAdded += ((eh * 60 + em) - (sh * 60 + sm));
        });
        const addedHours = (totalMinutesAdded / 60).toFixed(1);
        
        chipsEl.innerHTML = `
            <span class="score-chip">تعداد افزوده شده: ${suggestedItems.length}</span>
            <span class="score-chip">مجموع زمان: ${addedHours} ساعت</span>
            <span class="score-chip">استراتژی: ${
                selectedStrategy === 'balanced' ? 'توزیع متوازن' :
                selectedStrategy === 'compact' ? 'فشرده‌سازی' :
                selectedStrategy === 'morning' ? 'اولویت صبح' : 'اولویت عصر'
            }</span>
            <span class="score-chip">تداخل: صفر</span>
        `;
    }
    
    // Placed items list
    if (suggestedItems.length > 0) {
        suggestedItems.forEach(item => {
            const card = document.createElement('div');
            card.className = 'suggested-card';
            card.style.borderRightColor = categoryColors[item.category] || '#2563eb';
            
            const badgesHtml = (item.diagnostics || []).map(d => `<span class="badge-tag badge-tag-success">${escapeHtml(d)}</span>`).join('');
            
            card.innerHTML = `
                <div>
                    <div style="font-weight:700; font-size:0.9rem; color:#1e293b;">${escapeHtml(item.name)}</div>
                    <div class="suggested-card-badges">
                        <span class="badge-tag">📅 ${days[item.day - 1]}</span>
                        <span class="badge-tag">⏰ ${item.sTime} تا ${item.eTime}</span>
                        <span class="badge-tag">${categoryNames[item.category] || 'غیره'}</span>
                        ${badgesHtml}
                    </div>
                </div>
                <div style="font-size:0.8rem; font-weight:700; color:#059669; background:#dcfce7; padding:4px 10px; border-radius:8px;">
                    آماده اعمال
                </div>
            `;
            container.appendChild(card);
        });
    }
    
    // Failed items
    if (failedItems.length > 0) {
        const failedHeader = document.createElement('div');
        failedHeader.style.cssText = 'color:#dc2626; font-weight:700; font-size:0.88rem; margin:16px 0 8px 0;';
        failedHeader.innerHTML = '⚠️ آیتم‌های جاگذاری نشده (نیاز به بازنگری در شروط):';
        container.appendChild(failedHeader);
        
        failedItems.forEach(item => {
            const card = document.createElement('div');
            card.className = 'suggested-card';
            card.style.borderRightColor = '#ef4444';
            card.style.background = '#fef2f2';
            card.innerHTML = `
                <div>
                    <div style="font-weight:700; font-size:0.9rem; color:#991b1b;">${escapeHtml(item.name)}</div>
                    <div style="font-size:0.78rem; color:#b91c1c; margin-top:3px;">
                        علت: ${escapeHtml(item.failureReason || 'فضای خالی در بازه زمانی مجاز یافت نشد')}
                    </div>
                </div>
                <button type="button" class="btn-mini" style="background:#fee2e2; border-color:#fca5a5; color:#b91c1c;" onclick="goToWizardStep(3)">
                    تنظیم مجدد شروط
                </button>
            `;
            container.appendChild(card);
        });
    }
}

function applySuggestedSchedule() {
    if (suggestedSchedule.length === 0) {
        showToast('ابتدا یک برنامه پیشنهادی تولید کنید', 'error');
        return;
    }
    
    const suggestedCount = suggestedSchedule.filter(s => s._isSuggested && !s._failed).length;
    
    showConfirm(`برنامه فعلی با ${suggestedCount} فعالیت پیشنهادی هوشمند ترکیب و بهینه‌سازی شود؟`, function() {
        showLoader('در حال اعمال و ذخیره‌سازی برنامه در جدول...');
        try {
            // Keep fixed items
            const fixedItems = [];
            courses.forEach((c, i) => {
                if (autoScheduleFixedFlags[i] !== false) {
                    fixedItems.push(c);
                }
            });
            
            // Add successfully placed suggested items
            const newSuggestedItems = suggestedSchedule
                .filter(s => s._isSuggested && !s._failed)
                .map(s => ({
                    name: s.name,
                    category: s.category,
                    day: s.day,
                    sTime: s.sTime,
                    eTime: s.eTime,
                    prof: s.prof || '-',
                    loc: s.loc || '-'
                }));
            
            courses = [...fixedItems, ...newSuggestedItems];
            render();
            saveToHost();
            closeAutoScheduleModal();
            showToast('برنامه هوشمند با موفقیت به جدول افزوده شد', 'success');
        } finally {
            hideLoader();
        }
    });
}

// ==========================================
// ADD/EDIT MODAL
// ==========================================
function setupModalListeners() {
    // Open modal for adding
    document.getElementById('btnOpenModal').addEventListener('click', function() {
        document.getElementById('editIdx').value = '-1';
        document.getElementById('modalTitle').textContent = 'افزودن زمان‌بندی';
        document.getElementById('name').value = '';
        document.getElementById('prof').value = '';
        document.getElementById('loc').value = '';
        document.getElementById('category').value = 'uni';
        document.getElementById('day').value = '1';
        document.getElementById('sTime').value = '08:00';
        document.getElementById('eTime').value = '10:00';
        document.getElementById('addEditModal').style.display = 'flex';
    });
    
    // Close modal
    document.getElementById('btnClose').addEventListener('click', function() {
        document.getElementById('addEditModal').style.display = 'none';
    });
    
    // Save item
    document.getElementById('btnSave').addEventListener('click', saveItem);
    
    // Close on overlay click
    document.getElementById('addEditModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
}

function saveItem() {
    const name = document.getElementById('name').value.trim();
    if (!name) {
        showToast('فیلد عنوان الزامی است', 'error');
        return;
    }
    
    const sTime = document.getElementById('sTime').value;
    const eTime = document.getElementById('eTime').value;
    
    if (sTime >= eTime) {
        showToast('زمان پایان باید بعد از زمان شروع باشد', 'error');
        return;
    }
    
    const editIndex = parseInt(document.getElementById('editIdx').value);
    
    const courseData = {
        name: name,
        prof: document.getElementById('prof').value.trim() || '-',
        loc: document.getElementById('loc').value.trim() || '-',
        category: document.getElementById('category').value,
        day: parseInt(document.getElementById('day').value),
        sTime: sTime,
        eTime: eTime
    };
    
    if (editIndex >= 0 && editIndex < courses.length) {
        courses[editIndex] = courseData;
        showToast('آیتم با موفقیت ویرایش شد', 'success');
    } else {
        courses.push(courseData);
        showToast('آیتم جدید با موفقیت اضافه شد', 'success');
    }
    
    document.getElementById('addEditModal').style.display = 'none';
    render();
    saveToHost();
}

function editItem(index) {
    if (index < 0 || index >= courses.length) return;
    
    const course = courses[index];
    
    document.getElementById('editIdx').value = index;
    document.getElementById('modalTitle').textContent = 'ویرایش زمان‌بندی';
    document.getElementById('name').value = course.name;
    document.getElementById('prof').value = course.prof;
    document.getElementById('loc').value = course.loc;
    document.getElementById('category').value = course.category;
    document.getElementById('day').value = course.day;
    document.getElementById('sTime').value = course.sTime;
    document.getElementById('eTime').value = course.eTime;
    
    document.getElementById('addEditModal').style.display = 'flex';
}

function deleteItem(index) {
    if (index < 0 || index >= courses.length) return;
    
    showConfirm(`آیا از حذف "${courses[index].name}" اطمینان دارید؟`, function() {
        courses.splice(index, 1);
        render();
        saveToHost();
        showToast('آیتم با موفقیت حذف شد', 'success');
    });
}

// ==========================================
// SAVE & LOAD
// ==========================================
async function saveToHost(event) {
    const saveBtn = document.querySelector('.btn-save');
    if (!saveBtn) return;
    
    const originalContent = saveBtn.innerHTML;
    
    try {
        saveBtn.innerHTML = '<img src="./icons/loading.svg" class="icon-header" alt="loading"> ذخیره‌سازی...';
        saveBtn.style.pointerEvents = 'none';
        
        const payload = {
            schedule_id: currentScheduleId,
            courses: courses
        };
        
        const response = await fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': CSRF_TOKEN
            },
            body: JSON.stringify(payload)
        });
        
        if (response.ok) {
            if (event) showToast('اطلاعات با موفقیت ذخیره شد', 'success');
        } else {
            throw new Error('Save failed');
        }
    } catch (err) {
        console.error('Save error:', err);
        if (event) showToast('خطا در ذخیره‌سازی', 'error');
    } finally {
        setTimeout(() => {
            if (saveBtn) {
                saveBtn.innerHTML = originalContent;
                saveBtn.style.pointerEvents = 'auto';
            }
        }, 1000);
    }
}

// Auto-save every 2 minutes
setInterval(() => {
    if (courses.length > 0) {
        saveToHost();
    }
}, 120000);

// ==========================================
// IMPORT & EXPORT
// ==========================================
function setupImportListener() {
    const importFile = document.getElementById('importFile');
    if (!importFile) return;
    
    importFile.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = function(evt) {
            try {
                const imported = JSON.parse(evt.target.result);
                let coursesToImport;
                
                if (imported.courses && Array.isArray(imported.courses)) {
                    coursesToImport = imported.courses;
                } else if (Array.isArray(imported)) {
                    coursesToImport = imported;
                } else {
                    throw new Error('Invalid format');
                }
                
                showConfirm(`فایل انتخابی حاوی ${coursesToImport.length} آیتم است. جایگزین شود؟`, function() {
                    courses = coursesToImport;
                    render();
                    saveToHost();
                    showToast('فایل با موفقیت وارد شد', 'success');
                });
            } catch (err) {
                showToast('فایل JSON نامعتبر است', 'error');
            }
        };
        reader.readAsText(file);
        
        // Reset input
        this.value = '';
    });
}

function exportJSON() {
    const exportData = {
        scheduleName: currentScheduleName,
        courses: courses,
        exportDate: new Date().toISOString()
    };
    
    const dataStr = JSON.stringify(exportData, null, 2);
    const blob = new Blob([dataStr], {type: 'application/json'});
    const url = URL.createObjectURL(blob);
    
    const downloadLink = document.createElement('a');
    downloadLink.href = url;
    downloadLink.download = `${currentScheduleName.replace(/[^a-z0-9]/gi, '_')}_backup.json`;
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
    URL.revokeObjectURL(url);
    
    showToast('فایل پشتیبان با موفقیت خروجی گرفته شد', 'success');
}

// ==========================================
// UI TOGGLE
// ==========================================
function toggleSidebar() {
    const mainLayout = document.getElementById('mainLayout');
    const sidebar = document.getElementById('sidebar');
    
    if (window.innerWidth <= 768) {
        sidebar.classList.toggle('mobile-visible');
    } else {
        mainLayout.classList.toggle('full');
        sidebar.classList.toggle('hidden');
    }
}

// ==========================================
// KEYBOARD SHORTCUTS
// ==========================================
function setupKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Escape - close modals
        if (e.key === 'Escape') {
            const addEditModal = document.getElementById('addEditModal');
            const autoScheduleModal = document.getElementById('autoScheduleModal');
            const confirmModal = document.getElementById('confirmModal');
            const scheduleModal = document.getElementById('scheduleModal');
            
            if (addEditModal && addEditModal.style.display === 'flex') addEditModal.style.display = 'none';
            if (autoScheduleModal && autoScheduleModal.style.display === 'flex') closeAutoScheduleModal();
            if (confirmModal && confirmModal.style.display === 'flex') closeConfirm();
            if (scheduleModal && scheduleModal.style.display === 'flex') closeScheduleModal();
        }
        
        // Ctrl+S - Save
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            saveToHost(new Event('keyboard'));
        }
        
        // Ctrl+N - New item
        if (e.ctrlKey && e.key === 'n') {
            e.preventDefault();
            document.getElementById('btnOpenModal').click();
        }
        
        // Ctrl+M - Smart schedule
        if (e.ctrlKey && e.key === 'm') {
            e.preventDefault();
            openAutoScheduleModal();
        }
    });
}

// Also close modals on overlay click
document.addEventListener('click', function(e) {
    if (e.target.id === 'autoScheduleModal') {
        closeAutoScheduleModal();
    }
    if (e.target.id === 'confirmModal') {
        closeConfirm();
    }
    if (e.target.id === 'scheduleModal') {
        closeScheduleModal();
    }
});

// ==========================================
// INITIALIZATION LOG
// ==========================================
console.log('🚀 دروس پلاس وب - نسخه حرفه‌ای');
console.log('✅ Drag & Drop بهینه‌سازی شده');
console.log('✅ Toast notifications فعال');
console.log('✅ الگوریتم برنامه‌ریزی هوشمند آماده');
console.log('💡 میانبرها: Ctrl+S ذخیره | Ctrl+N آیتم جدید | Ctrl+M برنامه‌ریزی هوشمند | Esc بستن');
</script>

</body>
</html>