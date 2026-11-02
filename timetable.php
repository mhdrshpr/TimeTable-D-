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
    --primary-hover:#1d4ed8;
    --bg:#f8fafc;
    --card-bg:#ffffff;
    --header-bg:#ffffff;
    --sidebar-bg:#ffffff;
    --text-main:#0f172a;
    --text-muted:#64748b;
    --border:#cbd5e1;
    --cell-bg:#ffffff;
    --time-cell-bg:#f8fafc;
    --header-cell-bg:#f1f5f9;
    --modal-bg:#ffffff;
    --input-bg:#f8fafc;
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

body.dark-theme {
    --primary:#3b82f6;
    --primary-hover:#60a5fa;
    --bg:#0f172a;
    --card-bg:#1e293b;
    --header-bg:#1e293b;
    --sidebar-bg:#1e293b;
    --text-main:#f8fafc;
    --text-muted:#94a3b8;
    --border:#334155;
    --cell-bg:#1e293b;
    --time-cell-bg:#0b1120;
    --header-cell-bg:#0b1120;
    --modal-bg:#1e293b;
    --input-bg:#0f172a;
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

html {
    max-width: 100vw;
    overflow-x: hidden;
}

body{
    font-family:'IRANSansX',sans-serif;
    background:var(--bg);
    color:var(--text-main);
    margin:0;
    display:flex;
    flex-direction:column;
    height:100vh;
    overflow-x:hidden;
    max-width: 100vw;
    transition: background 0.25s, color 0.25s;
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
    background:var(--header-bg);
    border-bottom:1px solid var(--border);
    z-index: 100;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    transition: background 0.25s, border-color 0.25s;
    width: 100%;
    max-width: 100vw;
    overflow-x: hidden;
    box-sizing: border-box;
}

.header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 25px;
    gap: 15px;
}

.header-row-1 {
    border-bottom: 1px solid var(--border);
}

.header-row-2 {
    padding-top: 8px;
    padding-bottom: 8px;
    background: var(--header-bg);
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
    gap: 8px;
    margin: 0;
    font-size: 1.3rem;
    color: var(--text-main);
    white-space: nowrap;
}

.username-display {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--card-bg);
    border: 1px solid var(--border);
    padding: 6px 14px;
    border-radius: 10px;
    font-weight: bold;
    color: var(--primary);
    font-size: 0.9rem;
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
    gap: 6px;
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

.btn-theme, .btn-lang {
    background: var(--card-bg);
    border: 1px solid var(--border);
    color: var(--text-main);
    padding: 7px 12px;
}

.btn-theme:hover, .btn-lang:hover {
    background: var(--bg);
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
    color: var(--text-muted);
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
    color: #8b5cf6;
    padding: 6px 10px;
    font-size: 0.8rem;
}

.btn-mobile-schedules {
    display: none;
    background: rgba(37, 99, 235, 0.1);
    border: 1px solid rgba(37, 99, 235, 0.25);
    color: var(--primary);
}

.schedule-selector {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--card-bg);
    padding: 5px 8px;
    border-radius: 10px;
    border: 1px solid var(--border);
}

.schedule-selector select {
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid var(--border);
    font-family: inherit;
    background: var(--input-bg);
    color: var(--text-main);
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
    background:var(--sidebar-bg);
    border-left:1px solid var(--border);
    padding:15px;
    overflow-y:auto;
    transition:.3s;
    display: flex;
    flex-direction: column;
}

html[dir="ltr"] .sidebar {
    border-left: none;
    border-right: 1px solid var(--border);
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
    font-size: 0.95rem;
    color: var(--text-main);
    display: flex;
    align-items: center;
    gap: 6px;
}

.course-card{
    border:1px solid var(--border);
    padding:12px;
    border-radius:10px;
    margin-bottom:10px;
    background:var(--card-bg);
    color:var(--text-main);
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

html[dir="ltr"] .delete-btn {
    left: auto;
    right: 8px;
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
    color: var(--text-muted);
}

/* STATS */
.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-top: 10px;
}

.stat-item {
    background: var(--card-bg);
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    border: 1px solid var(--border);
}

.stat-label {
    font-size: 0.7rem;
    color: var(--text-muted);
    margin-bottom: 4px;
}

.stat-value {
    font-weight: bold;
    font-size: 0.9rem;
    color: var(--text-main);
}

.stat-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-left: 5px;
}

html[dir="ltr"] .stat-dot {
    margin-left: 0;
    margin-right: 5px;
}

/* GRID */
.grid-container{
    padding: 16px;
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    background: var(--bg);
    position: relative;
    width: 100%;
    max-width: 100%;
    min-width: 0;
    box-sizing: border-box;
}

.timetable{
    display: grid;
    grid-template-columns: 80px repeat(7, minmax(125px, 1fr));
    grid-template-rows: 45px repeat(19, var(--row-h));
    background: var(--border);
    gap: 1px;
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    min-width: 965px; /* 80px + 7*125px (875px) + 7*1px gaps + 2px borders = 964px */
    width: 100%;
    transition: filter 0.25s ease;
}

.cell{
    background:var(--cell-bg);
    display:flex;
    align-items:center;
    justify-content:center;
    position:relative;
    transition: background 0.15s, box-shadow 0.15s;
}

.cell.drag-hover {
    background: rgba(37, 99, 235, 0.2) !important;
    box-shadow: inset 0 0 0 3px var(--primary) !important;
    z-index: 50;
}

.header-cell{
    background:var(--header-cell-bg);
    color:var(--text-main);
    font-weight:bold;
    font-size: 0.85rem;
}

.time-cell{
    background:var(--time-cell-bg);
    color:var(--text-muted);
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

/* STEP 2: FLEXIBLE ITEMS (RESPONSIVE PRO) */
.flex-items-container {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-bottom: 14px;
}

.flex-card {
    background: var(--card-bg);
    border: 1.5px solid var(--border);
    border-radius: 16px;
    padding: 14px 16px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.03);
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 10px;
    transition: all 0.2s ease;
}

.flex-card:focus-within {
    border-color: var(--primary);
    box-shadow: 0 4px 16px rgba(37, 99, 235, 0.12);
}

.flex-header-row {
    display: flex;
    gap: 10px;
    align-items: center;
    width: 100%;
}

.flex-name-input {
    flex: 1;
    min-width: 0;
    padding: 9px 14px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-family: inherit;
    font-size: 0.92rem;
    background: var(--input-bg);
    color: var(--text-main);
    transition: border-color 0.2s;
}

.flex-name-input:focus {
    outline: none;
    border-color: var(--primary);
}

.flex-btn-delete {
    background: #fee2e2;
    border: none;
    border-radius: 10px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: background 0.2s;
}

.flex-btn-delete:hover {
    background: #fca5a5;
}

.flex-controls-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr 1fr 1fr;
    gap: 8px;
    width: 100%;
}

@media (max-width: 720px) {
    .flex-controls-grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 480px) {
    .flex-controls-grid {
        grid-template-columns: 1fr;
    }
}

.flex-select {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid var(--border);
    border-radius: 9px;
    font-family: inherit;
    font-size: 0.83rem;
    background: var(--card-bg);
    color: var(--text-main);
    cursor: pointer;
}

.flex-duration-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    background: var(--bg);
    padding: 8px 12px;
    border-radius: 10px;
    border: 1px solid var(--border);
}

.flex-duration-input-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
}

.flex-duration-input {
    width: 65px;
    padding: 5px 8px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-family: inherit;
    font-size: 0.82rem;
    background: var(--card-bg);
    color: var(--text-main);
    text-align: center;
}

.duration-chips {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.duration-chip {
    padding: 4px 9px;
    border-radius: 6px;
    font-size: 0.74rem;
    border: 1px solid var(--border);
    background: var(--card-bg);
    color: var(--text-main);
    cursor: pointer;
    font-family: inherit;
    font-weight: 600;
    transition: all 0.15s;
}

.duration-chip.selected {
    background: var(--primary);
    border-color: var(--primary);
    color: #ffffff;
}

/* STEP 3: STRATEGIES & CONSTRAINTS */
.strategy-picker {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 20px;
}

@media (max-width: 600px) {
    .strategy-picker {
        grid-template-columns: 1fr;
    }
}

.strategy-option {
    border: 1.5px solid var(--border);
    border-radius: 14px;
    padding: 12px 14px;
    cursor: pointer;
    background: var(--card-bg);
    color: var(--text-main);
    transition: all 0.2s;
    text-align: right;
}

.strategy-option:hover {
    border-color: #93c5fd;
}

.strategy-option.selected {
    border-color: var(--primary);
    background: rgba(37, 99, 235, 0.08);
    box-shadow: 0 0 0 1px var(--primary);
}

.strategy-option-title {
    font-weight: 700;
    font-size: 0.9rem;
    color: var(--text-main);
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.strategy-option-desc {
    font-size: 0.76rem;
    color: var(--text-muted);
    line-height: 1.4;
}

.rules-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    background: var(--bg);
    padding: 16px;
    border-radius: 14px;
    border: 1px solid var(--border);
}

@media (max-width: 600px) {
    .rules-grid {
        grid-template-columns: 1fr;
    }
}

.rule-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.rule-field label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-main);
}

.rule-field input, .rule-field select {
    padding: 8px 10px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-family: inherit;
    font-size: 0.85rem;
    background: var(--card-bg);
    color: var(--text-main);
}

/* STEP 4: PREVIEW & RESULTS (REDESIGNED ROW CARDS) */
.score-dashboard-pro {
    background: linear-gradient(135deg, #059669 0%, #0d9488 50%, #2563eb 100%);
    border-radius: 18px;
    padding: 18px 22px;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
    box-shadow: 0 10px 25px rgba(13, 148, 136, 0.25);
}

@media (max-width: 600px) {
    .score-dashboard-pro {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        padding: 16px;
    }
}

.score-badge-circle {
    width: 66px;
    height: 66px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(8px);
    border: 2.5px solid #ffffff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1.25rem;
    flex-shrink: 0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.score-badge-circle small {
    font-size: 0.62rem;
    font-weight: 600;
    opacity: 0.9;
}

.score-pro-meta {
    flex: 1;
}

.score-pro-title {
    font-size: 1.05rem;
    font-weight: 700;
    margin-bottom: 8px;
    letter-spacing: -0.3px;
}

.score-pro-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.score-pro-chip {
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255, 255, 255, 0.35);
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.76rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.suggestions-list-pro {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.suggested-row-card {
    position: relative;
    border-radius: 16px;
    padding: 16px 18px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    border-width: 1.5px;
    border-style: solid;
    transition: transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 3px 12px rgba(0,0,0,0.03);
}

.suggested-row-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(0,0,0,0.07);
}

.suggested-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.suggested-title-group {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.suggested-cat-pill {
    font-size: 0.72rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.suggested-activity-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--text-main);
}

.suggested-ready-badge {
    font-size: 0.76rem;
    font-weight: 700;
    color: #059669;
    background: #dcfce7;
    border: 1px solid #86efac;
    padding: 4px 12px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

body.dark-theme .suggested-ready-badge {
    background: rgba(5, 150, 105, 0.25);
    border-color: #059669;
    color: #6ee7b7;
}

.suggested-details-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.detail-tag {
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--card-bg);
    border: 1px solid var(--border);
    color: var(--text-main);
}

.suggested-diag-row {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    padding-top: 8px;
    border-top: 1px dashed var(--border);
}

.diag-pill {
    font-size: 0.74rem;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.diag-pill-preferred {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #bfdbfe;
}

.diag-pill-warning {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.diag-pill-shift {
    background: #f3e8ff;
    color: #6b21a8;
    border: 1px solid #e9d5ff;
}

.diag-pill-normal {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
}

body.dark-theme .diag-pill-preferred {
    background: rgba(30, 64, 175, 0.25);
    color: #93c5fd;
    border-color: #1d4ed8;
}

body.dark-theme .diag-pill-warning {
    background: rgba(146, 64, 14, 0.25);
    color: #fde047;
    border-color: #b45309;
}

body.dark-theme .diag-pill-shift {
    background: rgba(107, 33, 168, 0.25);
    color: #d8b4fe;
    border-color: #7e22ce;
}

body.dark-theme .diag-pill-normal {
    background: rgba(51, 65, 85, 0.3);
    color: #cbd5e1;
    border-color: #475569;
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

/* TABLE LOADER & BLUR */
.table-loader-overlay {
    position: absolute;
    inset: 0;
    background: rgba(248, 250, 252, 0.65);
    backdrop-filter: blur(5px);
    z-index: 50;
    display: none;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: opacity 0.2s ease;
}
body.dark-theme .table-loader-overlay {
    background: rgba(15, 23, 42, 0.7);
}
.table-loader-overlay.active {
    display: flex;
}
.timetable.is-loading {
    filter: blur(4px);
    pointer-events: none;
}
.table-loader-card {
    background: var(--card-bg);
    padding: 20px 28px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    border: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}

/* SKELETON LOADING IN SIDEBAR */
.skeleton-card {
    height: 75px;
    border-radius: 10px;
    margin-bottom: 10px;
    background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}
body.dark-theme .skeleton-card {
    background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%);
    background-size: 200% 100%;
}
.skeleton-stat {
    height: 52px;
    border-radius: 8px;
    background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}
body.dark-theme .skeleton-stat {
    background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%);
    background-size: 200% 100%;
}
@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* PRINT HEADER ON SCREEN IS HIDDEN */
.print-header {
    display: none;
}

/* MOBILE DRAWER & BOTTOM SHEET */
.sidebar-mobile-user-actions {
    display: none;
}
.btn-drawer-toggle {
    display: none;
}
.mobile-only-print {
    display: none;
}
.drawer-user-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--card-bg);
    border: 1px solid var(--border);
    padding: 10px 14px;
    border-radius: 12px;
}
.drawer-user-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    color: var(--text-main);
    font-size: 0.92rem;
}
.drawer-username {
    max-width: 140px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.drawer-pref-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}
.drawer-pref-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 10px;
    font-size: 0.8rem;
    background: var(--card-bg);
    border: 1px solid var(--border);
    color: var(--text-main);
    border-radius: 10px;
    cursor: pointer;
}
.drawer-actions-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 6px;
}
.drawer-action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 8px 6px;
    font-size: 0.78rem;
}
.drawer-divider {
    height: 1px;
    background: var(--border);
    margin: 4px 0 8px 0;
}

.sidebar-header-mobile {
    display: none;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 12px;
    margin-bottom: 12px;
    border-bottom: 1px solid var(--border);
    font-weight: 700;
    font-size: 1rem;
    color: var(--text-main);
}
.sidebar-close-btn {
    background: var(--bg);
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.3rem;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
}
.sidebar-sheet-handle {
    display: none;
}
.sidebar-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(4px);
    z-index: 2050;
    display: none;
}
.sidebar-backdrop.active {
    display: block;
}

.bottom-sheet-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 2200;
    display: none;
    align-items: flex-end;
}
.bottom-sheet-overlay.active {
    display: flex;
}
.bottom-sheet-content {
    background: var(--card-bg);
    width: 100%;
    max-height: 80vh;
    border-radius: 24px 24px 0 0;
    padding: 16px 20px 24px 20px;
    box-shadow: 0 -10px 40px rgba(0,0,0,0.25);
    transform: translateY(100%);
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
    box-sizing: border-box;
}
.bottom-sheet-overlay.active .bottom-sheet-content {
    transform: translateY(0);
}
.bottom-sheet-handle {
    width: 44px;
    height: 5px;
    background: var(--border);
    border-radius: 10px;
    margin: 0 auto 14px;
}
.bottom-sheet-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--border);
}
.bottom-sheet-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--text-muted);
}
.bottom-sheet-body {
    overflow-y: auto;
    max-height: 50vh;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.mobile-schedule-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 14px;
    border-radius: 12px;
    border: 1.5px solid var(--border);
    background: var(--bg);
    cursor: pointer;
    transition: all 0.2s;
}
.mobile-schedule-card.active {
    border-color: var(--primary);
    background: rgba(37, 99, 235, 0.1);
    font-weight: 700;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    body {
        overflow-x: hidden;
        overflow-y: auto;
        height: auto;
        max-width: 100vw;
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
        font-size: 0.85rem;
        padding: 5px 10px;
    }
    
    .btn-drawer-toggle {
        display: inline-flex !important;
        align-items: center;
        gap: 6px;
    }
    
    .mobile-only-print {
        display: inline-flex !important;
    }
    
    .desktop-only-action {
        display: none !important;
    }
    
    .sidebar-mobile-user-actions {
        display: flex !important;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 16px;
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
        display: none !important;
    }
    
    .btn-mobile-schedules {
        display: inline-flex !important;
    }
    
    .main {
        grid-template-columns: 1fr;
        flex: none;
        width: 100%;
        max-width: 100vw;
        overflow-x: hidden;
        min-width: 0;
    }
    
    .sidebar {
        display: flex !important;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        top: auto;
        width: 100%;
        max-width: 100vw;
        max-height: 85vh;
        z-index: 2100;
        background: var(--sidebar-bg);
        box-shadow: 0 -10px 40px rgba(0,0,0,0.4);
        border-radius: 24px 24px 0 0;
        transform: translateY(100%);
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid var(--border);
        border-bottom: none;
        overflow-y: auto;
        padding: 14px 20px 30px 20px;
        box-sizing: border-box;
    }
    
    html[dir="ltr"] .sidebar {
        right: 0;
        left: 0;
        top: auto;
        bottom: 0;
        transform: translateY(100%);
        box-shadow: 0 -10px 40px rgba(0,0,0,0.4);
    }
    
    .sidebar.drawer-open {
        transform: translateY(0) !important;
    }
    
    .sidebar-sheet-handle {
        display: block;
        width: 44px;
        height: 5px;
        background: var(--border);
        border-radius: 10px;
        margin: 0 auto 12px;
        flex-shrink: 0;
    }
    
    .sidebar-header-mobile {
        display: flex;
    }
    
    .grid-container {
        padding: 10px;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        max-width: 100vw;
        min-width: 0;
        box-sizing: border-box;
    }
    
    .timetable {
        min-width: 955px;
        width: max-content;
        grid-template-columns: 70px repeat(7, minmax(125px, 1fr));
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

/* ==========================================
   PRINT STYLESHEET (CLEAN A4 LANDSCAPE)
   ========================================== */
@media print {
    @page {
        size: landscape;
        margin: 6mm 8mm;
    }
    
    html, body {
        background: #ffffff !important;
        color: #000000 !important;
        height: auto !important;
        overflow: visible !important;
        font-size: 10pt !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Strictly hide all header, sidebar, buttons, modals, toasts, overlays */
    header,
    aside,
    .sidebar,
    .sidebar-backdrop,
    .bottom-sheet-overlay,
    .modal-overlay,
    .confirm-overlay,
    .schedule-modal-overlay,
    .toast-container,
    .global-loader-overlay,
    .table-loader-overlay,
    .btn,
    .drag-handle,
    .block-delete-btn {
        display: none !important;
        visibility: hidden !important;
    }
    
    .main {
        display: block !important;
        width: 100% !important;
        height: auto !important;
        overflow: visible !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .grid-container {
        display: block !important;
        width: 100% !important;
        height: auto !important;
        overflow: visible !important;
        margin: 0 !important;
        padding: 0 !important;
        background: transparent !important;
    }
    
    /* Show Print Header only in Print */
    .print-header {
        display: flex !important;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #0f172a;
        padding-bottom: 8px;
        margin-bottom: 12px;
    }
    
    .print-header h2 {
        margin: 0 0 4px 0;
        font-size: 1.25rem;
        color: #000000;
    }
    
    .print-header-meta {
        text-align: left;
        font-size: 0.82rem;
        color: #334155;
    }
    
    .timetable {
        display: grid !important;
        min-width: 100% !important;
        width: 100% !important;
        border: 2px solid #000000 !important;
        border-radius: 4px !important;
        background: #94a3b8 !important;
        gap: 1px !important;
        page-break-inside: avoid;
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
    
    .cell {
        background: #ffffff !important;
        border: none !important;
    }
    
    .header-cell, .time-cell {
        background: #e2e8f0 !important;
        color: #000000 !important;
        font-weight: 700 !important;
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
    
    .class-block {
        position: absolute !important;
        border-width: 1.5px !important;
        box-shadow: none !important;
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
        color: #000000 !important;
    }
}
</style>
</head>

<body>

<header>
    <div class="header-row header-row-1">
        <div class="header-right">
            <!-- Mobile Drawer Opener (visible only on mobile) -->
            <button type="button" class="btn btn-drawer-toggle" onclick="toggleSidebar()" title="منو و سایدبار">
                <img src="./icons/sidebar.svg" class="icon-header" alt="menu">
                <span data-i18n="sidebar">برنامه‌ها و منو</span>
            </button>
            
            <!-- Desktop User, Theme, Lang (hidden on mobile) -->
            <span class="username-display desktop-only-action">
                <img src="./icons/user.svg" class="icon-header" alt="user">
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span>
            <button type="button" class="btn btn-theme desktop-only-action" id="btnThemeToggle" onclick="toggleTheme()" title="تغییر تم">
                <span id="themeIcon"><img src="./icons/moon.svg" class="icon-header" alt="dark mode"></span>
            </button>
            <button type="button" class="btn btn-lang desktop-only-action" id="btnLangToggle" onclick="toggleLanguage()" title="Language">
                <span id="langLabel"><img src="./icons/language.svg" class="icon-header" alt="lang"> EN</span>
            </button>
        </div>
        
        <div class="header-center">
            <h2>
                <img src="./icons/calendar.svg" class="icon-main" alt="calendar">
                <span data-i18n="weeklyTimetable">زمان‌بندی هفتگی</span>
            </h2>
        </div>
        
        <div class="header-left">
            <button type="button" class="btn btn-print desktop-only-action" onclick="printTimetable()" title="چاپ">
                <img src="./icons/print.svg" class="icon-header" alt="print">
                <span class="btn-text" data-i18n="print">چاپ</span>
            </button>
            <button type="button" class="btn btn-save desktop-only-action" onclick="saveToHost(event)" title="ذخیره">
                <img src="./icons/save.svg" class="icon-header" alt="save">
                <span class="btn-text" data-i18n="save">ذخیره</span>
            </button>
            <button type="button" class="btn btn-io desktop-only-action" onclick="exportJSON()" title="خروجی JSON">
                <img src="./icons/export.svg" class="icon-header" alt="export">
                <span class="btn-text" data-i18n="export">خروجی</span>
            </button>
            <button type="button" class="btn btn-io desktop-only-action" onclick="document.getElementById('importFile').click()" title="ورودی JSON">
                <img src="./icons/import.svg" class="icon-header" alt="import">
                <span class="btn-text" data-i18n="import">ورودی</span>
            </button>
            <input type="file" id="importFile" accept=".json" style="display:none">
            <button type="button" class="btn btn-add" id="btnOpenModal" title="افزودن آیتم">
                <img src="./icons/add.svg" class="icon-header" alt="add">
                <span class="btn-text" data-i18n="add">افزودن</span>
            </button>
            <button type="button" class="btn btn-auto-schedule" onclick="openAutoScheduleModal()" title="برنامه‌ریزی هوشمند">
                <img src="./icons/magic.svg" class="icon-header" alt="auto">
                <span class="btn-text" data-i18n="smartSchedule">پیشنهاد هوشمند</span>
            </button>
        </div>
    </div>
    
    <div class="header-row header-row-2">
        <div class="header-right">
            <a href="logout.php" class="btn btn-logout desktop-only-action">
                <img src="./icons/logout.svg" class="icon-header" alt="logout"> <span data-i18n="logout">خروج</span>
            </a>
            <button type="button" class="btn btn-toggle desktop-only-action" onclick="toggleSidebar()">
                <img src="./icons/sidebar.svg" class="icon-header" alt="sidebar"> <span data-i18n="sidebar">سایدبار</span>
            </button>
            
            <!-- Mobile schedules sheet button -->
            <button type="button" class="btn btn-mobile-schedules" onclick="openMobileScheduleSheet()">
                <img src="./icons/calendar.svg" class="icon-header" alt="calendar"> <span data-i18n="schedules">انتخاب جدول</span>
            </button>
            <button type="button" class="btn btn-print mobile-only-print" onclick="printTimetable()" title="چاپ">
                <img src="./icons/print.svg" class="icon-header" alt="print">
                <span class="btn-text" data-i18n="print">چاپ</span>
            </button>
        </div>
        
        <div class="header-schedule-controls desktop-only-action">
            <div class="schedule-selector">
                <select id="scheduleSelect" onchange="switchSchedule()">
                    <option value="">بارگذاری...</option>
                </select>
                <button type="button" class="btn btn-schedule" onclick="showScheduleModal('create')" title="جدول جدید">
                    <img src="./icons/add_purple.svg" class="icon-header" alt="new"> <span data-i18n="newSchedule">جدید</span>
                </button>
                <button type="button" class="btn btn-schedule" onclick="showScheduleModal('rename')" title="تغییر نام">
                    <img src="./icons/edit_purple.svg" class="icon-header" alt="rename">
                </button>
                <button type="button" class="btn btn-schedule" onclick="deleteCurrentSchedule()" title="حذف جدول">
                    <img src="./icons/delete_purple.svg" class="icon-header" alt="delete">
                </button>
            </div>
        </div>
    </div>
</header>

<div class="main" id="mainLayout">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-sheet-handle"></div>
        <div class="sidebar-header-mobile">
            <span data-i18n="menuAndStats">منو و برنامه‌ها</span>
            <button type="button" class="sidebar-close-btn" onclick="toggleSidebar()">&times;</button>
        </div>
        
        <!-- Mobile Drawer Top Section: User, Settings & Actions -->
        <div class="sidebar-mobile-user-actions" id="sidebarMobileUserActions">
            <div class="drawer-user-card">
                <div class="drawer-user-info">
                    <img src="./icons/user.svg" class="icon" alt="user">
                    <span class="drawer-username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </div>
                <a href="logout.php" class="btn btn-logout drawer-logout-btn" title="خروج">
                    <img src="./icons/logout.svg" class="icon" alt="logout">
                    <span data-i18n="logout">خروج</span>
                </a>
            </div>
            
            <div class="drawer-pref-row">
                <button type="button" class="drawer-pref-btn" onclick="toggleTheme()" title="تغییر تم">
                    <img id="drawerThemeIcon" src="./icons/moon.svg" class="icon" alt="theme">
                    <span id="drawerThemeText">تم تاریک</span>
                </button>
                <button type="button" class="drawer-pref-btn" onclick="toggleLanguage()" title="Language">
                    <img src="./icons/language.svg" class="icon" alt="language">
                    <span id="drawerLangLabel">English</span>
                </button>
            </div>
            
            <div class="drawer-actions-row">
                <button type="button" class="btn btn-save drawer-action-btn" onclick="saveToHost(event)" title="ذخیره">
                    <img src="./icons/save.svg" class="icon" alt="save">
                    <span data-i18n="save">ذخیره</span>
                </button>
                <button type="button" class="btn btn-io drawer-action-btn" onclick="exportJSON()" title="خروجی JSON">
                    <img src="./icons/export.svg" class="icon" alt="export">
                    <span data-i18n="export">خروجی</span>
                </button>
                <button type="button" class="btn btn-io drawer-action-btn" onclick="document.getElementById('importFile').click()" title="ورودی JSON">
                    <img src="./icons/import.svg" class="icon" alt="import">
                    <span data-i18n="import">ورودی</span>
                </button>
            </div>
            <div class="drawer-divider"></div>
        </div>
        
        <div class="sidebar-section">
            <h3><img src="./icons/list.svg" class="icon" alt="list"> <span data-i18n="schedulesList">لیست زمان‌بندی‌ها</span></h3>
            <div id="courseList"></div>
        </div>
        
        <div class="sidebar-section">
            <h3><img src="./icons/chart.svg" class="icon" alt="stats"> <span data-i18n="categoryTotals">مجموع زمان هر دسته‌بندی</span></h3>
            <div id="categoryStats" class="stats-grid"></div>
        </div>
    </aside>

    <div class="grid-container">
        <!-- Print Header (visible only on print) -->
        <div class="print-header" id="printHeader">
            <div class="print-header-title">
                <h2 id="printTitle">برنامه هفتگی</h2>
                <div id="printSubtitle" style="font-size:0.9rem; font-weight:bold; color:#2563eb;">جدول اصلی</div>
            </div>
            <div class="print-header-meta">
                <div id="printUser">کاربر: <?php echo htmlspecialchars($_SESSION['username']); ?></div>
                <div id="printDate"></div>
            </div>
        </div>
        
        <!-- Targeted Table Loading Overlay & Blur -->
        <div id="tableLoaderOverlay" class="table-loader-overlay">
            <div class="table-loader-card">
                <div class="loader-spinner-ring"></div>
                <span class="loader-text" id="tableLoaderText">در حال بارگذاری جدول...</span>
            </div>
        </div>

        <div class="timetable" id="timetable"></div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal-overlay" id="addEditModal">
    <div class="modal-container">
        <h3 class="modal-title">
            <img src="./icons/add.svg" class="icon-header" alt="add">
            <span id="modalTitle" data-i18n="addSchedule">افزودن زمان‌بندی</span>
        </h3>
        
        <div class="form-grid">
            <input type="hidden" id="editIdx" value="-1">
            
            <input type="text" id="name" placeholder="عنوان" class="full-width">
            
            <input type="text" id="prof" placeholder="استاد / مربی">
            <input type="text" id="loc" placeholder="مکان">
            
            <select id="category" class="full-width">
                <option value="uni">کلاس دانشگاهی</option>
                <option value="nonuni">کلاس غیر دانشگاهی</option>
                <option value="plus">درس خواندن</option>
                <option value="sport">ورزش</option>
                <option value="book">کتاب</option>
                <option value="movie">فیلم و تفریح</option>
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
                    
                    <div class="score-dashboard-pro" id="scoreDashboard">
                        <div class="score-badge-circle">
                            <span id="scorePercent">۹۸٪</span>
                            <small>امتیاز</small>
                        </div>
                        <div class="score-pro-meta">
                            <div class="score-pro-title" id="scoreSummaryTitle">چیدمان بهینه و بدون تداخل زمانی</div>
                            <div class="score-pro-chips" id="scoreChipsList"></div>
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

<!-- Sidebar Backdrop (for Mobile Drawer) -->
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

<!-- Mobile Schedule Bottom Sheet -->
<div class="bottom-sheet-overlay" id="mobileScheduleSheetOverlay" onclick="closeMobileScheduleSheet(event)">
    <div class="bottom-sheet-content" id="mobileScheduleSheet">
        <div class="bottom-sheet-handle"></div>
        <div class="bottom-sheet-header">
            <h3 style="margin:0; display:flex; align-items:center; gap:8px; font-size:1.05rem; color:var(--text-main);">
                <img src="./icons/calendar.svg" class="icon-header" alt="calendar">
                <span data-i18n="manageSchedules">انتخاب و مدیریت جدول‌ها</span>
            </h3>
            <button type="button" class="bottom-sheet-close" onclick="closeMobileScheduleSheet()">&times;</button>
        </div>
        <div class="bottom-sheet-body" id="mobileSchedulesList"></div>
        <div style="margin-top:14px; display:flex; gap:8px;">
            <button type="button" class="btn btn-add" style="flex:1; justify-content:center; padding:10px;" onclick="closeMobileScheduleSheet(); showScheduleModal('create');">
                <img src="./icons/add.svg" class="icon-header" alt="add"> <span data-i18n="newSchedule">ایجاد جدول جدید</span>
            </button>
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
// THEME CONTROLLER (LIGHT / DARK)
// ==========================================
let currentTheme = localStorage.getItem('timetable_theme') || 
    (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

function applyTheme(theme) {
    currentTheme = theme;
    localStorage.setItem('timetable_theme', theme);
    const icon = document.getElementById('themeIcon');
    const drawerIcon = document.getElementById('drawerThemeIcon');
    const drawerText = document.getElementById('drawerThemeText');
    
    if (theme === 'dark') {
        document.body.classList.add('dark-theme');
        if (icon) icon.innerHTML = '<img src="./icons/sun.svg" class="icon-header" alt="light mode">';
        if (drawerIcon) drawerIcon.src = './icons/sun.svg';
        if (drawerText) drawerText.textContent = currentLang === 'en' ? 'Light Theme' : 'تم روشن';
    } else {
        document.body.classList.remove('dark-theme');
        if (icon) icon.innerHTML = '<img src="./icons/moon.svg" class="icon-header" alt="dark mode">';
        if (drawerIcon) drawerIcon.src = './icons/moon.svg';
        if (drawerText) drawerText.textContent = currentLang === 'en' ? 'Dark Theme' : 'تم تاریک';
    }
    renderTimetable();
}

function toggleTheme() {
    applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
}

// ==========================================
// BILINGUAL CONTROLLER (FA / EN)
// ==========================================
let currentLang = localStorage.getItem('timetable_lang') || 'fa';

const i18n = {
    fa: {
        weeklyTimetable: 'زمان‌بندی هفتگی',
        print: 'چاپ',
        save: 'ذخیره',
        saving: 'در حال ذخیره‌سازی...',
        export: 'خروجی',
        import: 'ورودی',
        add: 'افزودن',
        smartSchedule: 'پیشنهاد هوشمند',
        logout: 'خروج',
        sidebar: 'سایدبار',
        schedules: 'جدول‌ها',
        newSchedule: 'جدول جدید',
        renameSchedule: 'تغییر نام',
        deleteSchedule: 'حذف جدول',
        manageSchedules: 'انتخاب و مدیریت جدول‌ها',
        menuAndStats: 'منو و آمارها',
        schedulesList: 'لیست زمان‌بندی‌ها',
        categoryTotals: 'مجموع زمان هر دسته‌بندی',
        noItemsYet: 'هنوز هیچ آیتمی اضافه نشده',
        noStatsYet: 'آماری برای نمایش وجود ندارد',
        grandTotal: 'مجموع کل',
        addSchedule: 'افزودن زمان‌بندی',
        editSchedule: 'ویرایش زمان‌بندی',
        title: 'عنوان',
        teacher: 'استاد / مربی',
        location: 'مکان',
        category: 'دسته‌بندی',
        day: 'روز هفته',
        cancel: 'انصراف',
        confirm: 'تایید',
        delete: 'حذف',
        to: 'تا',
        hours: 'ساعت',
        minutes: 'دقیقه',
        loadingTable: 'در حال بارگذاری جدول...',
        scheduleChanged: 'جدول با موفقیت تغییر کرد',
        savedSuccess: 'اطلاعات با موفقیت ذخیره شد',
        deletedSuccess: 'آیتم با موفقیت حذف شد',
        addedSuccess: 'آیتم جدید با موفقیت اضافه شد',
        editedSuccess: 'آیتم با موفقیت ویرایش شد',
        deleteConfirm: 'آیا از حذف این آیتم اطمینان دارید؟',
        deleteScheduleConfirm: 'آیا از حذف این جدول و تمام اطلاعات آن اطمینان دارید؟',
        cantDeleteDefault: 'نمی‌توانید جدول اصلی را حذف کنید',
        exportSuccess: 'فایل پشتیبان با موفقیت خروجی گرفته شد',
        importSuccess: 'فایل با موفقیت وارد شد',
        timeOverlapWarning: 'زمان پایان باید بعد از زمان شروع باشد',
        titleRequired: 'فیلد عنوان الزامی است',
        lockCourses: 'تثبیت برنامه‌ها',
        addTasks: 'افزودن فعالیت‌ها',
        strategyAndRules: 'استراتژی و شروط',
        previewAndApply: 'پیش‌نمایش و اعمال',
        nextStep: 'مرحله بعد ➔',
        prevStep: 'بازگشت',
        generateSchedule: 'تولید و تحلیل برنامه ➔',
        applySchedule: 'اعمال برنامه به جدول',
        preferredDayHonored: 'روز ترجیحی کاملاً رعایت شد',
        preferredDayFull: 'به دلیل پر بودن روز انتخابی، به این روز منتقل شد'
    },
    en: {
        weeklyTimetable: 'Weekly Timetable',
        print: 'Print',
        save: 'Save',
        saving: 'Saving...',
        export: 'Export',
        import: 'Import',
        add: 'Add Item',
        smartSchedule: 'Smart Scheduler',
        logout: 'Logout',
        sidebar: 'Sidebar',
        schedules: 'Schedules',
        newSchedule: 'New Schedule',
        renameSchedule: 'Rename',
        deleteSchedule: 'Delete',
        manageSchedules: 'Select & Manage Schedules',
        menuAndStats: 'Menu & Statistics',
        schedulesList: 'Schedule Items',
        categoryTotals: 'Time by Category',
        noItemsYet: 'No items added yet',
        noStatsYet: 'No statistics available',
        grandTotal: 'Grand Total',
        addSchedule: 'Add Item',
        editSchedule: 'Edit Item',
        title: 'Title',
        teacher: 'Instructor / Coach',
        location: 'Location',
        category: 'Category',
        day: 'Day of Week',
        cancel: 'Cancel',
        confirm: 'Confirm',
        delete: 'Delete',
        to: 'to',
        hours: 'hrs',
        minutes: 'mins',
        loadingTable: 'Loading timetable...',
        scheduleChanged: 'Schedule changed successfully',
        savedSuccess: 'Saved successfully',
        deletedSuccess: 'Item deleted successfully',
        addedSuccess: 'New item added successfully',
        editedSuccess: 'Item updated successfully',
        deleteConfirm: 'Are you sure you want to delete this item?',
        deleteScheduleConfirm: 'Are you sure you want to delete this schedule and all its courses?',
        cantDeleteDefault: 'You cannot delete the default schedule',
        exportSuccess: 'Backup exported successfully',
        importSuccess: 'Backup imported successfully',
        timeOverlapWarning: 'End time must be after start time',
        titleRequired: 'Title field is required',
        lockCourses: 'Lock Courses',
        addTasks: 'Add Tasks',
        strategyAndRules: 'Strategy & Rules',
        previewAndApply: 'Preview & Apply',
        nextStep: 'Next Step ➔',
        prevStep: 'Back',
        generateSchedule: 'Generate & Analyze ➔',
        applySchedule: 'Apply to Timetable',
        preferredDayHonored: 'Preferred Day Honored',
        preferredDayFull: 'Preferred day full; placed on best day'
    }
};

function t(key) {
    return (i18n[currentLang] && i18n[currentLang][key]) ? i18n[currentLang][key] : key;
}

const daysFa = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
const daysEn = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
let days = currentLang === 'en' ? daysEn : daysFa;

const categoryNamesFa = {
    uni: 'کلاس دانشگاهی',
    sport: 'ورزش',
    nonuni: 'کلاس غیر دانشگاهی',
    plus: 'درس خواندن',
    work: 'کار',
    book: 'کتاب',
    movie: 'فیلم و تفریح',
    other: 'غیره'
};

const categoryNamesEn = {
    uni: 'University Class',
    sport: 'Sports',
    nonuni: 'Non-Uni Class',
    plus: 'Studying',
    work: 'Work',
    book: 'Reading Book',
    movie: 'Movie & Fun',
    other: 'Other'
};
let categoryNames = currentLang === 'en' ? categoryNamesEn : categoryNamesFa;

function applyLanguage(lang) {
    currentLang = lang;
    localStorage.setItem('timetable_lang', lang);
    days = lang === 'en' ? daysEn : daysFa;
    categoryNames = lang === 'en' ? categoryNamesEn : categoryNamesFa;
    
    document.documentElement.setAttribute('lang', lang);
    document.documentElement.setAttribute('dir', lang === 'en' ? 'ltr' : 'rtl');
    
    const langBtn = document.getElementById('langLabel');
    if (langBtn) {
        langBtn.innerHTML = lang === 'en' 
            ? '<img src="./icons/language.svg" class="icon-header" alt="lang"> فارسی' 
            : '<img src="./icons/language.svg" class="icon-header" alt="lang"> EN';
    }
    const drawerLang = document.getElementById('drawerLangLabel');
    if (drawerLang) {
        drawerLang.textContent = lang === 'en' ? 'فارسی' : 'English';
    }
    const drawerThemeText = document.getElementById('drawerThemeText');
    if (drawerThemeText) {
        drawerThemeText.textContent = currentTheme === 'dark' 
            ? (lang === 'en' ? 'Light Theme' : 'تم روشن')
            : (lang === 'en' ? 'Dark Theme' : 'تم تاریک');
    }
    
    // Update all elements with data-i18n
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (i18n[lang] && i18n[lang][key]) {
            el.textContent = i18n[lang][key];
        }
    });
    
    // Update day selects
    const daySelect = document.getElementById('day');
    if (daySelect) {
        const curVal = daySelect.value;
        daySelect.innerHTML = '';
        days.forEach((d, idx) => {
            const opt = document.createElement('option');
            opt.value = idx + 1;
            opt.textContent = d;
            if ((idx + 1) == curVal) opt.selected = true;
            daySelect.appendChild(opt);
        });
    }
    
    // Update category selects
    const catSelect = document.getElementById('category');
    if (catSelect) {
        const curVal = catSelect.value;
        catSelect.innerHTML = '';
        Object.entries(categoryNames).forEach(([catKey, catName]) => {
            const opt = document.createElement('option');
            opt.value = catKey;
            opt.textContent = catName;
            if (catKey === curVal) opt.selected = true;
            catSelect.appendChild(opt);
        });
    }
    
    render();
    if (document.getElementById('autoScheduleModal').style.display === 'flex') {
        updateWizardStepperUI();
        renderFixedItemsList();
    }
}

function toggleLanguage() {
    applyLanguage(currentLang === 'fa' ? 'en' : 'fa');
}

// ==========================================
// TARGETED LOADER & SKELETON CONTROLLER
// ==========================================
function showTableLoader(text) {
    const overlay = document.getElementById('tableLoaderOverlay');
    const table = document.getElementById('timetable');
    const textEl = document.getElementById('tableLoaderText');
    if (textEl) textEl.textContent = text || t('loadingTable');
    if (table) table.classList.add('is-loading');
    if (overlay) overlay.classList.add('active');
}

function hideTableLoader() {
    const overlay = document.getElementById('tableLoaderOverlay');
    const table = document.getElementById('timetable');
    if (table) table.classList.remove('is-loading');
    if (overlay) overlay.classList.remove('active');
}

function showSidebarSkeleton() {
    const courseList = document.getElementById('courseList');
    const statsGrid = document.getElementById('categoryStats');
    if (courseList) {
        courseList.innerHTML = `
            <div class="skeleton-card"></div>
            <div class="skeleton-card"></div>
            <div class="skeleton-card"></div>
        `;
    }
    if (statsGrid) {
        statsGrid.innerHTML = `
            <div class="skeleton-stat"></div>
            <div class="skeleton-stat"></div>
            <div class="skeleton-stat"></div>
            <div class="skeleton-stat"></div>
        `;
    }
}

function showLoader(text) {
    showTableLoader(text);
}

function hideLoader() {
    hideTableLoader();
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
// PRINT FUNCTION (A4 LANDSCAPE)
// ==========================================
function printTimetable() {
    const printTitle = document.getElementById('printTitle');
    const printSubtitle = document.getElementById('printSubtitle');
    const printUser = document.getElementById('printUser');
    const printDate = document.getElementById('printDate');
    
    if (printTitle) printTitle.textContent = currentLang === 'en' ? 'Weekly Timetable' : 'برنامه هفتگی';
    if (printSubtitle) printSubtitle.textContent = (currentLang === 'en' ? 'Schedule: ' : 'جدول: ') + currentScheduleName;
    if (printUser) printUser.textContent = (currentLang === 'en' ? 'User: ' : 'کاربر: ') + '<?php echo htmlspecialchars($_SESSION['username']); ?>';
    
    const now = new Date();
    const dateStr = currentLang === 'en' 
        ? now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' })
        : now.toLocaleDateString('fa-IR');
    if (printDate) printDate.textContent = (currentLang === 'en' ? 'Date: ' : 'تاریخ: ') + dateStr;
    
    window.print();
}

// ==========================================
// MOBILE DRAWER & BOTTOM SHEET CONTROLLERS
// ==========================================
function toggleSidebar() {
    const mainLayout = document.getElementById('mainLayout');
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    
    if (window.innerWidth <= 768) {
        const sheetOverlay = document.getElementById('mobileScheduleSheetOverlay');
        if (sheetOverlay) sheetOverlay.classList.remove('active');
        
        const isOpen = sidebar.classList.toggle('drawer-open');
        if (backdrop) backdrop.classList.toggle('active', isOpen);
    } else {
        mainLayout.classList.toggle('full');
        sidebar.classList.toggle('hidden');
    }
}

function openMobileScheduleSheet() {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    if (sidebar && sidebar.classList.contains('drawer-open')) {
        sidebar.classList.remove('drawer-open');
        if (backdrop) backdrop.classList.remove('active');
    }
    
    const sheetOverlay = document.getElementById('mobileScheduleSheetOverlay');
    const listEl = document.getElementById('mobileSchedulesList');
    if (!sheetOverlay || !listEl) return;
    
    listEl.innerHTML = '';
    
    schedules.forEach(schedule => {
        const isCurrent = schedule.id == currentScheduleId;
        const iconSrc = isCurrent ? './icons/schedule_selected.svg' : './icons/schedule_unselected.svg';
        const card = document.createElement('div');
        card.className = `mobile-schedule-card ${isCurrent ? 'active' : ''}`;
        card.innerHTML = `
            <div style="display:flex; align-items:center; gap:10px;">
                <img src="${iconSrc}" class="icon" style="width:22px; height:22px;" alt="${isCurrent ? 'selected' : 'unselected'}">
                <div>
                    <div style="font-weight:700; color:var(--text-main); font-size:0.95rem;">
                        ${escapeHtml(schedule.name)} ${schedule.is_default == 1 ? '<span style="font-size:0.75rem; color:var(--primary); font-weight:normal;">(' + (currentLang === 'en' ? 'Default' : 'اصلی') + ')</span>' : ''}
                    </div>
                </div>
            </div>
            <div style="display:flex; gap:6px;">
                <button type="button" class="btn btn-mini" onclick="event.stopPropagation(); closeMobileScheduleSheet(); showScheduleModal('rename');" title="${t('renameSchedule')}">
                    <img src="./icons/edit_purple.svg" class="icon" alt="edit">
                </button>
                ${schedule.is_default == 0 ? `
                <button type="button" class="btn btn-mini" style="background:#fee2e2; border-color:#fca5a5; color:#dc2626;" onclick="event.stopPropagation(); closeMobileScheduleSheet(); currentScheduleId = ${schedule.id}; deleteCurrentSchedule();" title="${t('deleteSchedule')}">
                    <img src="./icons/delete_purple.svg" class="icon" alt="delete">
                </button>
                ` : ''}
            </div>
        `;
        
        card.addEventListener('click', async () => {
            if (schedule.id != currentScheduleId) {
                closeMobileScheduleSheet();
                document.getElementById('scheduleSelect').value = schedule.id;
                await switchSchedule();
            } else {
                closeMobileScheduleSheet();
            }
        });
        
        listEl.appendChild(card);
    });
    
    sheetOverlay.classList.add('active');
}

function closeMobileScheduleSheet(e) {
    if (e && e.target && e.target.id !== 'mobileScheduleSheetOverlay' && !e.target.classList.contains('bottom-sheet-close')) {
        return;
    }
    const sheetOverlay = document.getElementById('mobileScheduleSheetOverlay');
    if (sheetOverlay) sheetOverlay.classList.remove('active');
}

// ==========================================
// INITIALIZATION
// ==========================================
window.addEventListener('DOMContentLoaded', async function() {
    applyTheme(currentTheme);
    applyLanguage(currentLang);
    
    showTableLoader();
    showSidebarSkeleton();
    
    try {
        await loadSchedulesList();
        await loadCurrentSchedule();
    } catch (e) {
        console.error('Initialization error:', e);
    } finally {
        hideTableLoader();
    }
    
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
        option.textContent = schedule.name + (schedule.is_default == 1 ? ' (' + (currentLang === 'en' ? 'Default' : 'اصلی') + ')' : '');
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
        showTableLoader();
        showSidebarSkeleton();
        try {
            await saveCurrentScheduleSilent();
            currentScheduleId = newId;
            const schedule = schedules.find(s => s.id == newId);
            if (schedule) currentScheduleName = schedule.name;
            await loadCurrentSchedule();
            showToast(t('scheduleChanged'), 'info');
        } catch (e) {
            showToast('خطا در تغییر جدول', 'error');
        } finally {
            hideTableLoader();
        }
    }
}

async function loadCurrentSchedule() {
    showTableLoader();
    showSidebarSkeleton();
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
    } finally {
        hideTableLoader();
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
        
        const isDark = document.body.classList.contains('dark-theme');
        const block = document.createElement('div');
        block.className = 'class-block';
        block.setAttribute('data-course-index', index);
        block.style.borderColor = categoryColors[course.category];
        block.style.color = isDark ? '#f8fafc' : '#0f172a';
        block.style.background = isDark 
            ? `linear-gradient(135deg, ${categoryColors[course.category]}30, ${categoryColors[course.category]}48)`
            : `linear-gradient(135deg, ${categoryColors[course.category]}14, ${categoryColors[course.category]}28)`;
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
            <span style="font-size:0.7rem">${categoryNames[course.category] || ''} | ${escapeHtml(course.loc)}</span>
            <span style="font-size:0.7rem">${course.sTime} ${t('to')} ${course.eTime}</span>
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
                
                const courseName = courses[index] ? courses[index].name : t('thisItem');
                showConfirm(`${t('deleteConfirm')} ("${courseName}")`, function() {
                    courses.splice(index, 1);
                    render();
                    saveToHost();
                    showToast(`"${courseName}" ${t('deletedSuccess')}`, 'success');
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
        list.innerHTML = `<div style="text-align:center; color:#94a3b8; padding:20px;">${t('noItemsYet')}</div>`;
        return;
    }
    
    // Sort by day and time
    const sortedCourses = [...courses].sort((a, b) => {
        if (a.day !== b.day) return a.day - b.day;
        return a.sTime.localeCompare(b.sTime);
    });
    
    const isRtl = document.documentElement.getAttribute('dir') !== 'ltr';
    
    sortedCourses.forEach((course) => {
        const originalIndex = courses.indexOf(course);
        const card = document.createElement('div');
        card.className = 'course-card';
        if (isRtl) {
            card.style.borderRight = `6px solid ${categoryColors[course.category]}`;
            card.style.borderLeft = 'none';
        } else {
            card.style.borderLeft = `6px solid ${categoryColors[course.category]}`;
            card.style.borderRight = 'none';
        }
        
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
                <img src="./icons/clock.svg" class="icon" alt="clock"> ${days[course.day-1]} | ${course.sTime} ${t('to')} ${course.eTime}
            </div>
            <div class="card-info">
                <img src="./icons/folder.svg" class="icon" alt="folder"> ${categoryNames[course.category] || ''}
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
        statsContainer.innerHTML = `<div style="text-align:center; color:#94a3b8; padding:10px; font-size:0.8rem;">${t('noStatsYet')}</div>`;
        return;
    }
    
    Object.entries(totals).forEach(([category, minutes]) => {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        const timeStr = hours > 0 ? `${hours}:${mins.toString().padStart(2, '0')} ${t('hours')}` : `${mins} ${t('minutes')}`;
        
        const statItem = document.createElement('div');
        statItem.className = 'stat-item';
        statItem.innerHTML = `
            <span class="stat-dot" style="background:${categoryColors[category]}"></span>
            <div class="stat-label">${categoryNames[category] || ''}</div>
            <div class="stat-value">${timeStr}</div>
        `;
        statsContainer.appendChild(statItem);
    });
    
    // Grand total
    const totalHours = Math.floor(grandTotal / 60);
    const totalMins = grandTotal % 60;
    const totalStr = totalHours > 0 ? `${totalHours}:${totalMins.toString().padStart(2, '0')} ${t('hours')}` : `${totalMins} ${t('minutes')}`;
    
    const totalItem = document.createElement('div');
    totalItem.className = 'stat-item';
    totalItem.style.gridColumn = 'span 2';
    totalItem.innerHTML = `
        <div class="stat-label"><img src="./icons/clock.svg" class="icon" alt="clock"> ${t('grandTotal')}</div>
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
        prevBtn.innerHTML = `➔ ${t('prevStep')}`;
    }
    
    if (nextBtn) {
        if (currentWizardStep === 4) {
            nextBtn.style.display = 'none';
        } else {
            nextBtn.style.display = 'inline-flex';
            if (currentWizardStep === 3) {
                nextBtn.innerHTML = `<img src="./icons/magic_filled.svg" class="icon-header" alt="magic"> ${t('generateSchedule')}`;
            } else {
                nextBtn.innerHTML = t('nextStep');
            }
        }
    }
    
    if (applyBtn) {
        applyBtn.style.display = currentWizardStep === 4 ? 'inline-flex' : 'none';
        applyBtn.innerHTML = `<img src="./icons/save.svg" class="icon" alt="apply"> ${t('applySchedule')}`;
    }
    
    // Slide transition in RTL vs LTR
    const track = document.getElementById('wizardSliderTrack');
    if (track) {
        const isRtl = document.documentElement.getAttribute('dir') !== 'ltr';
        if (isRtl) {
            track.style.marginLeft = '0';
            track.style.marginRight = `-${(currentWizardStep - 1) * 100}%`;
        } else {
            track.style.marginRight = '0';
            track.style.marginLeft = `-${(currentWizardStep - 1) * 100}%`;
        }
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
                <span class="fixed-status-label" style="display:inline-flex; align-items:center; gap:4px; font-size:0.75rem; color:${isLocked ? '#2563eb' : '#64748b'}; font-weight:${isLocked ? '700' : 'normal'};">
                    <img src="${isLocked ? './icons/lock.svg' : './icons/unlock.svg'}" class="icon" alt="${isLocked ? 'lock' : 'unlock'}">
                    <span>${isLocked ? (currentLang === 'en' ? 'Locked' : 'قفل شده') : (currentLang === 'en' ? 'Free' : 'آزاد')}</span>
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
            const labelEl = card.querySelector('.fixed-status-label');
            if (labelEl) {
                const isChk = this.checked;
                labelEl.innerHTML = `
                    <img src="${isChk ? './icons/lock.svg' : './icons/unlock.svg'}" class="icon" alt="${isChk ? 'lock' : 'unlock'}">
                    <span>${isChk ? (currentLang === 'en' ? 'Locked' : 'قفل شده') : (currentLang === 'en' ? 'Free' : 'آزاد')}</span>
                `;
                labelEl.style.color = isChk ? '#2563eb' : '#64748b';
                labelEl.style.fontWeight = isChk ? '700' : 'normal';
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
    
    const isEn = currentLang === 'en';
    const catOptions = Object.entries(categoryNames).map(([k, v]) => 
        `<option value="${k}" ${k === 'plus' ? 'selected' : ''}>${v}</option>`
    ).join('');

    const dayOptions = `<option value="0">${isEn ? 'Day: Any day' : 'روز: هر روزی'}</option>` +
        days.map((d, idx) => `<option value="${idx + 1}">${d}</option>`).join('');

    const prioOptions = isEn ? `
        <option value="high">Priority: High ⭐</option>
        <option value="medium" selected>Priority: Normal</option>
        <option value="low">Priority: Low</option>
    ` : `
        <option value="high">اولویت: ضروری ⭐</option>
        <option value="medium" selected>اولویت: معمولی</option>
        <option value="low">اولویت: دلخواه</option>
    `;

    const windowOptions = isEn ? `
        <option value="any">Window: Any time</option>
        <option value="morning">Morning (8 - 12)</option>
        <option value="afternoon">Afternoon (12 - 17)</option>
        <option value="evening">Evening & Night (17 - 22)</option>
    ` : `
        <option value="any">بازه: هر ساعتی</option>
        <option value="morning">بازه: صبح (۸ تا ۱۲)</option>
        <option value="afternoon">بازه: بعدازظهر (۱۲ تا ۱۷)</option>
        <option value="evening">بازه: عصر و شب (۱۷ تا ۲۲)</option>
    `;

    const card = document.createElement('div');
    card.className = 'flex-card';
    card.id = itemId;
    card.innerHTML = `
        <div class="flex-header-row">
            <input type="text" placeholder="${isEn ? 'Activity title (e.g. Study Math, Workout, ...)' : 'عنوان فعالیت (مثلاً مطالعه ریاضی، ورزش، ...)'}" class="flex-name-input" autofocus>
            <button type="button" class="flex-btn-delete" onclick="removeFlexibleItem('${itemId}')" title="${isEn ? 'Delete item' : 'حذف این فعالیت'}">
                <img src="./icons/delete.svg" class="icon" alt="delete" style="filter:invert(23%) sepia(85%) saturate(3000%) hue-rotate(345deg);">
            </button>
        </div>
        
        <div class="flex-controls-grid">
            <select class="flex-select flex-category" title="${isEn ? 'Category' : 'دسته‌بندی'}">
                ${catOptions}
            </select>
            
            <select class="flex-select flex-preferred-day" title="${isEn ? 'Preferred Day' : 'روز ترجیحی'}">
                ${dayOptions}
            </select>
            
            <select class="flex-select flex-priority" title="${isEn ? 'Priority' : 'اولویت انجام'}">
                ${prioOptions}
            </select>
            
            <select class="flex-select flex-time-window" title="${isEn ? 'Time Window' : 'ترجیح بازه زمانی'}">
                ${windowOptions}
            </select>
        </div>
        
        <div class="flex-duration-bar">
            <div class="flex-duration-input-wrap">
                <span style="font-size:0.78rem; font-weight:700; color:var(--text-main);">${isEn ? 'Duration:' : 'مدت زمان:'}</span>
                <input type="number" class="flex-duration-input" value="90" min="15" max="360" step="15">
                <span style="font-size:0.75rem; color:var(--text-muted);">${isEn ? 'mins' : 'دقیقه'}</span>
            </div>
            
            <div class="duration-chips">
                <span class="duration-chip" onclick="setFlexDuration('${itemId}', 45)">${isEn ? '45m' : '۴۵د'}</span>
                <span class="duration-chip" onclick="setFlexDuration('${itemId}', 60)">${isEn ? '1 hour' : '۱ ساعت'}</span>
                <span class="duration-chip selected" onclick="setFlexDuration('${itemId}', 90)">${isEn ? '1.5 hours' : '۱.۵ ساعت'}</span>
                <span class="duration-chip" onclick="setFlexDuration('${itemId}', 120)">${isEn ? '2 hours' : '۲ ساعت'}</span>
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
        
        // Helper function to search slots on a given list of days
        function findBestSlotOnDays(daysList, isFallback) {
            let localBestDay = -1;
            let localBestSlot = -1;
            let localBestScore = -Infinity;
            let localBestDiag = [];
            
            for (const day of daysList) {
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
                        let score = 100;
                        const diag = [];
                        
                        // Preferred Day Match or Fallback
                        if (item.preferredDay > 0 && day === item.preferredDay - 1) {
                            score += 60;
                            diag.push(t('preferredDayHonored'));
                        } else if (item.preferredDay > 0 && isFallback) {
                            diag.push(t('preferredDayFull'));
                        }
                        
                        // Preferred Time Window Match
                        if (item.timeWindow === 'morning') {
                            if (startSlot >= 4 && startSlot < 24) {
                                score += 30;
                                diag.push(currentLang === 'en' ? 'Morning shift' : 'شیفت صبح لحاظ شد');
                            } else {
                                score -= 20;
                            }
                        } else if (item.timeWindow === 'afternoon') {
                            if (startSlot >= 24 && startSlot < 44) {
                                score += 30;
                                diag.push(currentLang === 'en' ? 'Afternoon shift' : 'شیفت بعدازظهر لحاظ شد');
                            } else {
                                score -= 20;
                            }
                        } else if (item.timeWindow === 'evening') {
                            if (startSlot >= 44 && startSlot < 64) {
                                score += 30;
                                diag.push(currentLang === 'en' ? 'Evening shift' : 'شیفت عصر و شب لحاظ شد');
                            } else {
                                score -= 20;
                            }
                        }
                        
                        // Strategy Scoring
                        if (constraints.strategy === 'morning') {
                            score += Math.max(0, (40 - startSlot));
                        } else if (constraints.strategy === 'afternoon') {
                            if (startSlot >= 28) score += 25;
                        } else if (constraints.strategy === 'balanced') {
                            score -= (dayLoads[day] * 3);
                        } else if (constraints.strategy === 'compact') {
                            if (dayLoads[day] > 0) score += 20;
                        }
                        
                        // Lunch conflict penalty
                        if (lunchConflict) {
                            score -= 50;
                        }
                        
                        // Adjacency reward
                        if (startSlot > 0 && (weekGrid[day][startSlot - 1] === 1 || weekGrid[day][startSlot - 1] === 2)) {
                            score += 15;
                        }
                        
                        if (score > localBestScore) {
                            localBestScore = score;
                            localBestDay = day;
                            localBestSlot = startSlot;
                            localBestDiag = diag;
                        }
                    }
                }
            }
            
            return { day: localBestDay, slot: localBestSlot, score: localBestScore, diag: localBestDiag };
        }
        
        // STRICT PREFERRED DAY LOGIC:
        // If user specified a preferred day, evaluate ONLY that day first.
        // Only if it doesn't fit on the preferred day, evaluate other days as fallback.
        if (item.preferredDay > 0) {
            const pDayIdx = item.preferredDay - 1;
            const prefRes = findBestSlotOnDays([pDayIdx], false);
            if (prefRes.day !== -1) {
                bestDay = prefRes.day;
                bestSlot = prefRes.slot;
                bestScore = prefRes.score;
                bestDiagnostics = prefRes.diag;
            } else {
                const otherDays = dayCandidates.filter(d => d !== pDayIdx);
                const fallbackRes = findBestSlotOnDays(otherDays, true);
                bestDay = fallbackRes.day;
                bestSlot = fallbackRes.slot;
                bestScore = fallbackRes.score;
                bestDiagnostics = fallbackRes.diag;
            }
        } else {
            const normalRes = findBestSlotOnDays(dayCandidates, false);
            bestDay = normalRes.day;
            bestSlot = normalRes.slot;
            bestScore = normalRes.score;
            bestDiagnostics = normalRes.diag;
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
    
    const isEn = currentLang === 'en';
    const suggestedItems = schedule.filter(s => s._isSuggested && !s._failed);
    const failedItems = schedule.filter(s => s._failed);
    const totalItems = suggestedItems.length + failedItems.length;
    
    // Calculate optimization score
    let score = totalItems > 0 ? Math.round((suggestedItems.length / totalItems) * 100) : 100;
    if (score === 100 && failedItems.length === 0) {
        score = 98; // Realistic high score
    }
    
    const scoreCircle = document.getElementById('scorePercent');
    if (scoreCircle) scoreCircle.textContent = `${score}%`;
    
    const titleEl = document.getElementById('scoreSummaryTitle');
    if (titleEl) {
        if (failedItems.length === 0) {
            titleEl.textContent = isEn ? '✨ Optimal, balanced schedule without any time conflicts' : '✨ چیدمان بهینه، متعادل و بدون تداخل زمانی';
        } else {
            titleEl.textContent = isEn 
                ? `⚠️ ${suggestedItems.length} of ${totalItems} activities successfully scheduled` 
                : `⚠️ ${suggestedItems.length} از ${totalItems} فعالیت با موفقیت جایابی شدند`;
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
        
        const stratMapFa = {
            balanced: 'توزیع متوازن',
            compact: 'فشرده‌سازی',
            morning: 'اولویت صبح',
            afternoon: 'اولویت عصر'
        };
        const stratMapEn = {
            balanced: 'Balanced Distribution',
            compact: 'Compaction',
            morning: 'Morning Priority',
            afternoon: 'Afternoon Priority'
        };
        const stratName = (isEn ? stratMapEn : stratMapFa)[selectedStrategy] || selectedStrategy;
        
        chipsEl.innerHTML = `
            <span class="score-pro-chip">📋 ${isEn ? 'Added' : 'تعداد افزوده شده'}: ${suggestedItems.length}</span>
            <span class="score-pro-chip">⏱️ ${isEn ? 'Total time' : 'مجموع زمان'}: ${addedHours} ${t('hours')}</span>
            <span class="score-pro-chip">⚖️ ${isEn ? 'Strategy' : 'استراتژی'}: ${stratName}</span>
            <span class="score-pro-chip">🛡️ ${isEn ? 'Conflicts: 0' : 'تداخل زمانی: صفر'}</span>
        `;
    }
    
    // Placed items list - Colorful Row Cards
    if (suggestedItems.length > 0) {
        const isRtl = document.documentElement.getAttribute('dir') !== 'ltr';
        const listWrapper = document.createElement('div');
        listWrapper.className = 'suggestions-list-pro';
        
        suggestedItems.forEach(item => {
            const card = document.createElement('div');
            card.className = 'suggested-row-card';
            
            const catColor = categoryColors[item.category] || '#2563eb';
            const [sh, sm] = item.sTime.split(':').map(Number);
            const [eh, em] = item.eTime.split(':').map(Number);
            const dur = (eh * 60 + em) - (sh * 60 + sm);
            const durHours = (dur / 60).toFixed(dur % 60 === 0 ? 0 : 1);
            
            // Set dynamic colorful border & pastel background
            card.style.borderColor = `${catColor}40`;
            card.style.background = `linear-gradient(135deg, ${catColor}10, ${catColor}22)`;
            if (isRtl) {
                card.style.borderRight = `6px solid ${catColor}`;
            } else {
                card.style.borderLeft = `6px solid ${catColor}`;
            }
            
            // Build diagnostic pills
            let diagHtml = '';
            if (item.diagnostics && item.diagnostics.length > 0) {
                const pills = item.diagnostics.map(d => {
                    let pillClass = 'diag-pill-normal';
                    if (d.includes('ترجیحی') || d.includes('Preferred')) {
                        pillClass = d.includes('پر بودن') || d.includes('full') ? 'diag-pill-warning' : 'diag-pill-preferred';
                    } else if (d.includes('صبح') || d.includes('بعدازظهر') || d.includes('عصر') || d.includes('shift')) {
                        pillClass = 'diag-pill-shift';
                    }
                    return `<span class="diag-pill ${pillClass}">📌 ${escapeHtml(d)}</span>`;
                }).join('');
                diagHtml = `<div class="suggested-diag-row">${pills}</div>`;
            }
            
            card.innerHTML = `
                <div class="suggested-card-top">
                    <div class="suggested-title-group">
                        <span class="suggested-cat-pill" style="background:${catColor}; color:#ffffff;">
                            ${categoryNames[item.category] || ''}
                        </span>
                        <div class="suggested-activity-title">${escapeHtml(item.name)}</div>
                    </div>
                    <div class="suggested-ready-badge">
                        <img src="./icons/check.svg" class="icon" alt="ready" style="width:14px; height:14px;">
                        <span>${isEn ? 'Ready to Apply' : 'آماده اعمال'}</span>
                    </div>
                </div>
                
                <div class="suggested-details-row">
                    <span class="detail-tag">
                        <img src="./icons/calendar.svg" class="icon" alt="day">
                        <b>${days[item.day - 1]}</b>
                    </span>
                    <span class="detail-tag">
                        <img src="./icons/clock.svg" class="icon" alt="clock">
                        <b>${item.sTime} ${t('to')} ${item.eTime}</b>
                    </span>
                    <span class="detail-tag">
                        <span>⏱️ ${dur} ${t('minutes')} (${durHours} ${t('hours')})</span>
                    </span>
                </div>
                
                ${diagHtml}
            `;
            
            listWrapper.appendChild(card);
        });
        
        container.appendChild(listWrapper);
    }
    
    // Failed items
    if (failedItems.length > 0) {
        const failedHeader = document.createElement('div');
        failedHeader.style.cssText = 'color:#dc2626; font-weight:700; font-size:0.92rem; margin:20px 0 10px 0; display:flex; align-items:center; gap:6px;';
        failedHeader.innerHTML = isEn 
            ? '⚠️ Unplaced items (review your constraints):' 
            : '⚠️ فعالیت‌های جاگذاری‌نشده (نیاز به بازنگری در شروط):';
        container.appendChild(failedHeader);
        
        failedItems.forEach(item => {
            const card = document.createElement('div');
            card.className = 'suggested-row-card';
            card.style.borderColor = '#fca5a5';
            card.style.background = '#fef2f2';
            card.style.borderInlineStart = '6px solid #ef4444';
            
            card.innerHTML = `
                <div class="suggested-card-top">
                    <div class="suggested-title-group">
                        <span class="suggested-cat-pill" style="background:#ef4444; color:#ffffff;">
                            ${categoryNames[item.category] || ''}
                        </span>
                        <div class="suggested-activity-title" style="color:#991b1b;">${escapeHtml(item.name)}</div>
                    </div>
                    <button type="button" class="btn-mini" style="background:#fee2e2; border-color:#fca5a5; color:#b91c1c; padding:6px 14px; border-radius:8px;" onclick="goToWizardStep(3)">
                        ${isEn ? 'Reconfigure Rules' : 'تنظیم مجدد شروط (مرحله ۳)'}
                    </button>
                </div>
                
                <div style="font-size:0.82rem; color:#b91c1c; margin-top:4px; display:flex; align-items:center; gap:6px;">
                    <span>⚠️</span>
                    <span><b>${isEn ? 'Reason' : 'علت عدم قرارگیری'}:</b> ${escapeHtml(item.failureReason || (isEn ? 'No available slot within time boundaries' : 'فضای خالی در بازه زمانی مجاز با توجه به سقف ساعت روزانه یافت نشد'))}</span>
                </div>
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
// KEYBOARD SHORTCUTS & OVERLAYS
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
            closeMobileScheduleSheet();
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
console.log('🚀 Timetable Web App');
console.log('✅ Drag & Drop ready');
console.log('✅ Smart Scheduler active');
</script>

</body>
</html>