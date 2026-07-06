<style>
body {
    margin: 0;
    font-family: "Courier New", Courier, monospace;
    background: linear-gradient(180deg, #f7f5ef 0%, #e4e1d5 100%);
    color: #111;
}

body::before {
    content: "";
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: -1;
    opacity: 0.15;
    background-image: linear-gradient(rgba(0,0,0,0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,0.05) 1px, transparent 1px);
    background-size: 36px 36px;
}

.container {
    max-width: 900px;
    margin: auto;
    padding: 40px;
}

h2 {
    margin: 0 0 10px;
    font-size: 20px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    border-bottom: 2px solid #111;
    padding-bottom: 8px;
}

button,
a.button {
    background: #fff;
    color: #111;
    padding: 10px 16px;
    border: 3px solid #111;
    border-radius: 4px;
    text-decoration: none;
    display: inline-block;
    margin-top: 10px;
    cursor: pointer;
    transition: transform 0.1s ease, background 0.1s ease;
    font-family: "Courier New", Courier, monospace;
    box-shadow: 4px 4px 0 #111;
}

button:hover,
a.button:hover {
    background: #111;
    color: #fff;
    transform: translate(-2px, -2px);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th,
table td {
    border-bottom: 2px solid #111;
    padding: 10px;
    text-align: left;
}

table th {
    background: #fff;
}

table th {
    font-weight: 500;
}

input,
select {
    width: 100%;
    padding: 10px 12px;
    border: 3px solid #111;
    margin-top: 5px;
    box-sizing: border-box;
    font-family: "Courier New", Courier, monospace;
    border-radius: 0;
    background: #f5f5f5;
}

input:focus,
select:focus,
textarea:focus {
    outline: none;
    box-shadow: inset 0 0 0 3px #111;
}

a {
    color: black;
}

.flash-message {
    border: 1px solid #111;
    padding: 12px 14px;
    margin-bottom: 18px;
    background: #fff;
}

.flash-message.success {
    background: #111;
    color: #fff;
}

.flash-message.error {
    background: #fff;
    color: #111;
}

.dashboard-shell {
    max-width: 1220px;
    margin: 0 auto;
    padding: 34px 28px 56px;
}

.dashboard-hero {
    display: flex;
    justify-content: space-between;
    gap: 24px;
    align-items: flex-start;
    border-bottom: 1px solid #111;
    padding-bottom: 28px;
}

.admin-dashboard-header {
    display: flex;
    justify-content: space-between;
    gap: 24px;
    align-items: flex-start;
    margin-bottom: 24px;
    padding: 24px;
    border: 2px solid #111;
    border-radius: 24px;
    background: linear-gradient(135deg, #ffffff 0%, #f5f2e8 100%);
    box-shadow: 8px 8px 0 #e5e7eb;
}

.admin-header-copy h1 {
    margin: 0;
    font-size: 40px;
    line-height: 1.05;
}

.admin-header-copy p {
    margin: 10px 0 0;
    max-width: 680px;
    color: #555;
}

.admin-header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.admin-dashboard-layout {
    display: grid;
    grid-template-columns: 280px minmax(0, 1fr);
    gap: 24px;
    align-items: start;
}

.admin-sidebar {
    display: grid;
    gap: 16px;
}

.admin-sidebar-card {
    border: 2px solid #111;
    border-radius: 18px;
    background: rgba(255,255,255,0.95);
    padding: 16px;
    box-shadow: 6px 6px 0 #e5e7eb;
}

.admin-sidebar-card strong {
    display: block;
    margin-bottom: 10px;
}

.admin-sidebar-card ul {
    margin: 0;
    padding-left: 18px;
    color: #555;
    display: grid;
    gap: 6px;
}

.admin-link-list {
    display: grid;
    gap: 8px;
}

.admin-link-list a {
    text-decoration: none;
    color: #111;
    padding: 6px 0;
    border-bottom: 1px solid #eee;
}

.admin-main-stack {
    display: grid;
    gap: 18px;
}

.dashboard-kicker {
    margin: 0 0 8px;
    font-size: 13px;
}

.profile-card {
    min-width: 240px;
    display: grid;
    grid-template-columns: 46px 1fr;
    gap: 12px;
    align-items: center;
    border: 2px solid #111;
    padding: 14px;
    border-radius: 16px;
    background: #fff;
    box-shadow: 6px 6px 0 #e5e7eb;
}

.profile-avatar {
    width: 46px;
    height: 46px;
    display: grid;
    place-items: center;
    background: #111;
    color: #fff;
    font-size: 20px;
}

.profile-card strong,
.profile-card span,
.profile-card a {
    display: block;
}

.profile-card span {
    color: #555;
    font-size: 13px;
}

.profile-card a {
    grid-column: 2;
    font-size: 13px;
}

.summary-grid,
.dashboard-grid {
    display: grid;
    gap: 16px;
    margin-top: 20px;
}

.summary-grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
}

.two-columns {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.three-columns {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}

.metric-card,
.dashboard-card,
.profile-card,
.dashboard-note,
.transport-form-card,
.transport-preview-card {
    border: 2px solid #111;
    background: rgba(255,255,255,0.95);
    padding: 18px;
    border-radius: 18px;
    box-shadow: 6px 6px 0 #e5e7eb;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.metric-card:hover,
.dashboard-card:hover,
.profile-card:hover,
.dashboard-note:hover,
.transport-form-card:hover,
.transport-preview-card:hover {
    transform: translateY(-2px);
    box-shadow: 8px 8px 0 #d1d5db;
}

.metric-card span,
.metric-card small {
    display: block;
}

.metric-card span,
.section-title span,
.metric-card small {
    color: #555;
    font-size: 13px;
}

.metric-card strong {
    display: block;
    margin: 12px 0 8px;
    font-size: 28px;
    font-weight: 500;
    line-height: 1.1;
}

.metric-card.alert {
    background: #111;
    color: #fff;
}

.metric-card.alert span,
.metric-card.alert small {
    color: #ddd;
}

.dashboard-tools {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: end;
    margin-top: 22px;
}

.dashboard-note {
    flex: 1;
    display: flex;
    gap: 12px;
    align-items: flex-start;
    padding: 14px 16px;
    border: 2px solid #111;
    border-radius: 16px;
    background: #fafafa;
    box-shadow: 4px 4px 0 #e5e7eb;
}

.dashboard-note strong {
    display: block;
    margin-bottom: 4px;
}

.dashboard-note span {
    color: #555;
    font-size: 13px;
    line-height: 1.5;
}

.dashboard-filter {
    flex: 1;
    display: grid;
    grid-template-columns: 2fr repeat(3, 1fr);
    gap: 10px;
}

.dashboard-filter input,
.dashboard-filter select {
    height: 44px;
    margin: 0;
    border-color: #111;
    background: #fff;
    border-radius: 999px;
    padding: 0 14px;
}

.quick-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

a.button.secondary {
    background: #fff;
    color: #000;
}

a.button.secondary:hover {
    background: #000;
    color: #fff;
}

.section-title {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: baseline;
    margin-bottom: 16px;
    border-bottom: 1px solid #111;
    padding-bottom: 8px;
}

.section-title h2 {
    margin: 0;
}

.section-title p,
.section-title span {
    margin: 0;
    padding: 0;
    border: 0;
    font-size: 22px;
    font-weight: 500;
}

.pie-wrap {
    display: flex;
    align-items: center;
    gap: 22px;
}

.pie-chart {
    width: 148px;
    aspect-ratio: 1;
    border-radius: 50%;
    border: 1px solid #111;
    background: conic-gradient(#111 var(--good), #fff 0);
}

.chart-legend {
    display: grid;
    gap: 10px;
}

.chart-legend span {
    display: flex;
    align-items: center;
    gap: 8px;
}

.chart-legend i {
    width: 14px;
    height: 14px;
    border: 1px solid #111;
}

.legend-good {
    background: #111;
}

.legend-broken {
    background: #fff;
}

.bar-list,
.type-stats,
.alert-list,
.settings-list,
.stats-list {
    display: grid;
    gap: 12px;
}

.bar-row {
    display: grid;
    grid-template-columns: minmax(90px, 1fr) 2fr 38px;
    gap: 10px;
    align-items: center;
}

.bar-row div {
    height: 12px;
    border: 1px solid #111;
}

.bar-row b {
    display: block;
    height: 100%;
    background: #111;
}

.bar-row em {
    font-style: normal;
    text-align: right;
}

.line-chart {
    position: relative;
    height: 180px;
    border-left: 1px solid #111;
    border-bottom: 1px solid #111;
    background: repeating-linear-gradient(to top, #f2f2f2 0, #f2f2f2 1px, transparent 1px, transparent 36px);
}

.line-point {
    position: absolute;
    width: 12px;
    height: 12px;
    transform: translate(-50%, 50%);
    border: 1px solid #111;
    background: #111;
}

.line-labels {
    display: flex;
    justify-content: space-between;
    gap: 8px;
    margin-top: 10px;
    color: #555;
    font-size: 12px;
}

.type-stats progress {
    width: 100%;
    height: 10px;
    accent-color: #111;
}

.type-stats strong,
.type-stats span {
    display: block;
}

.type-stats span {
    color: #555;
    font-size: 13px;
}

.wide {
    grid-column: span 2;
}

.dashboard-table {
    margin-top: 0;
}

.dashboard-table th,
.dashboard-table td {
    font-size: 14px;
}

.dashboard-table th {
    background: #f4f1e8;
    color: #222;
}

.status-pill {
    display: inline-block;
    border: 1px solid #111;
    padding: 3px 8px;
    border-radius: 999px;
    font-size: 12px;
}

.status-pill.good {
    background: #111;
    color: #fff;
}

.status-pill.bad {
    background: #fff;
    color: #111;
}

.alert-list a {
    display: block;
    border-bottom: 1px solid #ddd;
    padding: 8px 0 10px;
    text-decoration: none;
    border-radius: 8px;
}

.alert-list strong,
.alert-list span {
    display: block;
}

.alert-list span,
.muted {
    color: #555;
    font-size: 13px;
}

.mini-calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
}

.mini-calendar span {
    min-height: 32px;
    display: grid;
    place-items: center;
    border: 1px solid #ddd;
    font-size: 13px;
}

.mini-calendar .today {
    border-color: #111;
    background: #111;
    color: #fff;
}

.mini-calendar .has-event {
    border-color: #111;
}

.settings-list p,
.stats-list p {
    margin: 0;
    display: flex;
    justify-content: space-between;
    gap: 16px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
}

.settings-list span,
.stats-list span {
    color: #555;
    text-align: right;
}

.transport-shell {
    max-width: 1180px;
    margin: 0 auto;
    padding: 34px 28px 56px;
}

.transport-hero {
    display: flex;
    justify-content: space-between;
    gap: 24px;
    align-items: flex-start;
    border-bottom: 1px solid #111;
    padding-bottom: 28px;
}

.transport-hero h1 {
    margin: 0;
    font-size: 42px;
    font-weight: 500;
    line-height: 1.08;
}

.transport-hero p {
    margin: 10px 0 0;
    max-width: 560px;
    color: #555;
}

.transport-table-card {
    margin-top: 20px;
}

.booking-table-caption {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    margin-bottom: 18px;
}

.booking-table-caption h2 {
    margin: 0;
    font-size: 24px;
}

.booking-table-caption small {
    display: block;
    color: #555;
    margin-top: 4px;
}

.booking-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.booking-shell {
    max-width: 1220px;
    margin: 0 auto;
    padding: 34px 28px 56px;
}

.booking-topbar {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 24px;
    align-items: start;
    padding: 24px;
    border: 4px solid #111;
    border-radius: 24px;
    background: #fff;
    box-shadow: 10px 10px 0 #111;
}

.breadcrumb {
    margin: 0 0 10px;
    color: #777;
    font-size: 13px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
}

.booking-topbar h1 {
    margin: 0;
    font-size: 42px;
    line-height: 1.05;
    letter-spacing: 0.08em;
}

.booking-intro {
    margin: 12px 0 0;
    color: #222;
    max-width: 600px;
    line-height: 1.7;
}

.booking-actions-top {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.booking-dashboard-grid {
    display: grid;
    grid-template-columns: minmax(360px, 1fr) 320px;
    gap: 24px;
    margin-top: 28px;
}

.booking-main-card,
.booking-sidebar,
.booking-courts-overview {
    width: 100%;
}

.booking-main-card,
.booking-sidebar .dashboard-card,
.booking-courts-overview .dashboard-card {
    border: 4px solid #111;
    background: #fff;
    padding: 24px;
    border-radius: 18px;
    box-shadow: 10px 10px 0 #111;
}

.booking-form {
    display: grid;
    gap: 18px;
}

.booking-form label {
    display: grid;
    gap: 10px;
}

.booking-form label span {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.12em;
}

.booking-form input,
.booking-form select {
    height: 50px;
    margin: 0;
    border: 3px solid #111;
    border-radius: 0;
    background: #fdfdfd;
    padding: 0 14px;
    font-size: 15px;
}

.booking-form textarea {
    min-height: 90px;
    padding: 12px 14px;
    resize: vertical;
}

.booking-form input:focus,
.booking-form select:focus,
.booking-form textarea:focus {
    outline: none;
    box-shadow: inset 0 0 0 3px #111;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.form-actions {
    display: flex;
    gap: 14px;
    align-items: center;
    flex-wrap: wrap;
}

.form-actions button,
.form-actions .button {
    min-width: 140px;
}

.booking-preview-stack {
    display: grid;
    gap: 16px;
}

.booking-court-card {
    display: grid;
    gap: 14px;
    border: 4px solid #111;
    padding: 20px;
    border-radius: 18px;
    background: #fff;
    box-shadow: 10px 10px 0 #111;
}

.booking-court-card.compact {
    grid-template-columns: 100px 1fr;
    align-items: center;
}

.booking-court-image {
    width: 100px;
    height: 78px;
    object-fit: cover;
    border-radius: 16px;
    border: 3px solid #111;
}

.booking-court-content h3,
.booking-court-card h3 {
    margin: 0 0 8px;
    font-size: 20px;
    letter-spacing: 0.05em;
}

.booking-court-content p,
.booking-court-card p {
    margin: 0;
    color: #555;
    font-size: 14px;
    line-height: 1.6;
}

.booking-court-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.booking-court-meta li,
.booking-court-meta strong {
    display: inline-block;
    padding: 8px 10px;
    border: 2px solid #111;
    border-radius: 10px;
    background: #f7f5ef;
    color: #111;
    font-size: 13px;
}

.compact-card input[type="search"] {
    width: 100%;
    padding: 12px 14px;
    border: 3px solid #111;
    border-radius: 999px;
    background: #fdfdfd;
    margin: 12px 0 16px;
}

.quick-list,
.featured-links {
    display: grid;
    gap: 10px;
}

.quick-list a,
.featured-links a {
    text-decoration: none;
    color: #111;
    padding: 12px 14px;
    border: 3px solid #111;
    border-radius: 0;
    background: #fff;
    box-shadow: 4px 4px 0 #111;
    transition: background 0.2s ease, color 0.2s ease, transform 0.1s ease;
}

.quick-list a:hover,
.featured-links a:hover {
    background: #111;
    color: #fff;
    transform: translate(-2px, -2px);
}

.featured-card {
    border: 3px solid #111;
    border-radius: 0;
    padding: 18px;
    background: #fff;
}

.mini-calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
}

.mini-calendar span {
    min-height: 38px;
    display: grid;
    place-items: center;
    border-radius: 12px;
    background: #fff;
    border: 3px solid #111;
    color: #333;
    font-size: 14px;
}

.mini-calendar .today {
    background: #111;
    color: #fff;
}

.booking-court-card.compact img {
    width: 100px;
    height: 80px;
    border-radius: 14px;
    object-fit: cover;
    border: 3px solid #111;
}

.booking-court-meta strong {
    color: #111;
}

.booking-court-image {
    width: 100%;
    aspect-ratio: 16 / 9;
    object-fit: cover;
    border-radius: 18px;
    border: 3px solid #111;
}

.dashboard-grid.three-columns.booking-court-grid {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}

.transport-table-card table th,
.transport-table-card table td {
    padding: 14px 12px;
}

.transport-table-card table tr {
    border-bottom: 1px solid #f0f0f0;
}

.transport-table-card table tbody tr:last-child {
    border-bottom: 0;
}

.button.secondary {
    border-color: #111;
    background: transparent;
    color: #111;
}

.button.secondary:hover {
    background: #111;
    color: #fff;
}

.transport-toolbar input,
.transport-toolbar select,
.transport-toolbar button {
    height: 44px;
    margin: 0;
    border-color: #111;
    border-radius: 999px;
}

.transport-toolbar button {
    background: #fff;
    color: #111;
    padding: 8px 12px;
}

.transport-toolbar button:hover {
    background: #111;
    color: #fff;
}

.transport-table-card {
    margin-top: 20px;
}

.table-scroll {
    width: 100%;
    overflow-x: auto;
}

.transport-thumb {
    width: 74px;
    height: 54px;
    display: block;
    object-fit: cover;
    border: 1px solid #111;
}

.thumb-empty {
    width: 74px;
    height: 54px;
    display: grid;
    place-items: center;
    border: 1px solid #ddd;
    color: #555;
    font-size: 12px;
}

.row-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.row-actions a {
    text-underline-offset: 4px;
}

.inline-form {
    margin: 0;
}

.inline-form select {
    width: auto;
    height: 34px;
    margin: 0;
    padding: 4px 8px;
    border-color: #111;
}

.transport-form-layout {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 16px;
    margin-top: 20px;
}

.transport-form-card,
.transport-preview-card {
    border: 1px solid #111;
    background: #fff;
    padding: 22px;
}

.transport-form-card {
    margin-top: 20px;
}

.transport-form-layout .transport-form-card {
    margin-top: 0;
}

.transport-form {
    display: grid;
    gap: 16px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.transport-form label {
    display: grid;
    gap: 8px;
}

.transport-form label span {
    font-size: 14px;
}

.transport-form input,
.transport-form select {
    height: 42px;
    margin: 0;
    border-color: #111;
    background: #fff;
}

.file-field input {
    height: auto;
    padding: 10px;
}

.file-field small {
    color: #555;
}

.form-actions {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-top: 4px;
}

.form-actions button {
    min-width: 120px;
}

.form-actions a {
    text-underline-offset: 4px;
}

.transport-preview-card {
    align-self: start;
    display: grid;
    gap: 14px;
}

.transport-preview-card img,
.preview-empty {
    width: 100%;
    aspect-ratio: 4 / 3;
    border: 1px solid #111;
}

.booking-court-image {
    width: 100%;
    aspect-ratio: 16 / 9;
    object-fit: cover;
    border: 1px solid #111;
    margin-bottom: 12px;
}

.booking-qr-code {
    width: 72px;
    height: 72px;
    display: block;
    object-fit: contain;
    border: 1px solid #111;
    background: #fff;
}

.receipt-page {
    background: #f4f4f4;
    color: #111;
}

.receipt-shell {
    min-height: 100vh;
    display: grid;
    place-items: center;
    padding: 20px;
}

.receipt-card {
    width: min(100%, 460px);
    background: #fff;
    border: 1px solid #111;
    padding: 22px;
}

.receipt-header {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: flex-start;
}

.receipt-header h1 {
    margin: 4px 0 0;
    font-size: 28px;
}

.receipt-number {
    margin: 18px 0;
    padding: 12px;
    border: 1px solid #111;
    font-size: 24px;
    font-weight: 800;
    text-align: center;
}

.receipt-list {
    display: grid;
    gap: 10px;
}

.receipt-list p {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    margin: 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #ddd;
}

.receipt-list span {
    text-align: right;
}

.receipt-print {
    width: 100%;
    margin-top: 18px;
}

.booking-hero {
    display: grid;
    grid-template-columns: 1.25fr 0.75fr;
    gap: 24px;
    align-items: center;
    margin-top: 20px;
    padding: 30px;
    border: 4px solid #111;
    border-radius: 0;
    background: #fff;
    color: #111;
    box-shadow: 8px 8px 0 #111;
}

.booking-hero-user {
    background: #fff;
    color: #111;
}

.booking-hero-copy h1 {
    margin: 0 0 10px;
    font-size: 36px;
    line-height: 1.05;
    font-family: "Courier New", Courier, monospace;
    text-transform: uppercase;
    letter-spacing: 0.14em;
}

.booking-hero-copy p {
    margin: 0;
    color: #111;
    max-width: 620px;
    line-height: 1.6;
}

.booking-hero-highlights {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 18px;
}

.booking-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 12px;
    border-radius: 0;
    background: #fff;
    border: 3px solid #111;
    font-size: 13px;
    color: #111;
    box-shadow: 4px 4px 0 #111;
}

.booking-hero-card {
    padding: 22px;
    border-radius: 0;
    background: #fff;
    border: 4px solid #111;
    box-shadow: 8px 8px 0 #111;
}

.booking-hero-card h3 {
    margin: 0 0 10px;
    font-size: 20px;
    letter-spacing: 0.08em;
}

.booking-hero-card ul {
    margin: 0;
    padding-left: 18px;
    color: #111;
    display: grid;
    gap: 8px;
}

.booking-hero-card-accent {
    background: #fff;
    border: 3px solid #111;
}

.booking-preview-stack {
    display: grid;
    gap: 16px;
}

.metric-card {
    border-radius: 18px;
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
}

.dashboard-card.transport-table-card {
    border-radius: 24px;
    box-shadow: 0 18px 36px rgba(15, 23, 42, 0.06);
}

.transport-preview-card {
    border-radius: 24px;
    box-shadow: 0 18px 36px rgba(15, 23, 42, 0.06);
}

.transport-form-card {
    border-radius: 24px;
    box-shadow: 0 18px 36px rgba(15, 23, 42, 0.06);
}

@media (max-width: 980px) {
    .booking-hero,
    .transport-form-layout,
    .summary-grid,
    .dashboard-grid.three-columns.booking-court-grid {
        grid-template-columns: 1fr;
    }

    .booking-hero-card {
        order: -1;
    }
}

@media print {
    .receipt-page {
        background: #fff;
    }

    .receipt-shell {
        min-height: auto;
        padding: 0;
    }

    .receipt-card {
        width: 100%;
        border: 0;
    }

    .receipt-print {
        display: none;
    }
}

.transport-preview-card img {
    object-fit: cover;
}

.preview-empty {
    display: grid;
    place-items: center;
    color: #555;
}

.transport-preview-card strong,
.transport-preview-card span {
    display: block;
}

.transport-preview-card span {
    color: #555;
    margin-top: 4px;
}

.location-layout {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 16px;
    margin-top: 20px;
}

.location-list {
    display: grid;
    gap: 10px;
}

.location-item {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: center;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
}

.location-item a {
    text-underline-offset: 4px;
}

@media print {
    .navbar,
    .no-print {
        display: none !important;
    }

    body {
        background: #fff;
    }

    .transport-shell,
    .dashboard-shell {
        max-width: none;
        padding: 0;
    }

    .metric-card,
    .dashboard-card,
    .transport-form-card,
    .transport-preview-card {
        break-inside: avoid;
    }
}

.auth-page {
    min-height: 100vh;
    background:
        linear-gradient(90deg, #f5f5f5 1px, transparent 1px),
        linear-gradient(#f5f5f5 1px, transparent 1px),
        #fff;
    background-size: 48px 48px;
}

.auth-shell {
    min-height: 100vh;
    display: grid;
    place-items: center;
    padding: 24px;
    box-sizing: border-box;
}

.auth-panel {
    width: min(100%, 420px);
    border: 2px solid #111;
    background: #fff;
    padding: 36px;
    box-sizing: border-box;
    border-radius: 24px;
    box-shadow: 10px 10px 0 #e5e7eb;
}

.auth-kicker {
    margin: 0 0 22px;
    font-size: 13px;
    letter-spacing: 0;
    text-transform: uppercase;
}

.auth-panel h1 {
    margin: 0 0 28px;
    font-size: 34px;
    font-weight: 500;
    line-height: 1.1;
}

.auth-form {
    display: grid;
    gap: 12px;
}

.auth-form label {
    font-size: 14px;
}

.auth-form input,
.auth-form select {
    height: 46px;
    margin: 0 0 6px;
    border: 2px solid #111;
    background: #fff;
    font-size: 15px;
    border-radius: 10px;
    padding: 0 12px;
    box-shadow: 4px 4px 0 #f3f4f6;
}

.auth-form input:focus,
.auth-form select:focus {
    outline: 2px solid #111;
    outline-offset: 2px;
}

.auth-form button {
    width: 100%;
    height: 46px;
    margin-top: 8px;
    font-size: 15px;
}

.auth-message {
    border: 2px solid #111;
    border-radius: 12px;
    padding: 10px 12px;
    margin-bottom: 18px;
    font-size: 14px;
    box-shadow: 4px 4px 0 #f3f4f6;
}

.auth-message.success {
    background: #111;
    color: #fff;
}

.auth-switch {
    margin: 22px 0 0;
    font-size: 14px;
    text-align: center;
}

.auth-switch a {
    text-decoration-thickness: 1px;
    text-underline-offset: 4px;
}

@media (max-width: 900px) {
    .dashboard-hero,
    .dashboard-tools,
    .transport-hero {
        display: grid;
    }

    .summary-grid,
    .two-columns,
    .three-columns,
    .dashboard-filter,
    .transport-toolbar,
    .transport-form-layout,
    .form-grid,
    .location-layout {
        grid-template-columns: 1fr;
    }

    .wide {
        grid-column: auto;
    }

    .profile-card {
        min-width: 0;
    }
}

@media (max-width: 480px) {
    .dashboard-shell {
        padding: 24px 16px 42px;
    }

    .transport-shell {
        padding: 24px 16px 42px;
    }

    .dashboard-hero h1,
    .transport-hero h1 {
        font-size: 32px;
    }

    .pie-wrap {
        display: grid;
    }

    .auth-panel {
        padding: 28px 22px;
    }

    .auth-panel h1 {
        font-size: 30px;
    }
}
</style>
