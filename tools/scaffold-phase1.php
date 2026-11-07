<?php

declare(strict_types=1);

/**
 * Phase 1 scaffold generator for Last-Mile Delivery Optimizer.
 * Run: php tools/scaffold-phase1.php
 */

$root = dirname(__DIR__);

function write(string $path, string $contents): void
{
    $dir = dirname($path);
    if (! is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($path, $contents);
    echo "Wrote {$path}\n";
}

$modules = [
    // CORE (always on — not toggleable as off for platform integrity, but listed)
    ['key' => 'core', 'name' => 'Business & Organization', 'category' => 'core', 'icon' => 'heroicon-o-building-office-2', 'dependencies' => [], 'default' => true, 'tier' => 'core', 'always_on' => true, 'description' => 'Business profile, branding, localization, currency, timezone, and units.'],
    ['key' => 'users_roles', 'name' => 'Users, Roles & Permissions', 'category' => 'core', 'icon' => 'heroicon-o-users', 'dependencies' => ['core'], 'default' => true, 'tier' => 'core', 'always_on' => true, 'description' => 'Teams, invitations, granular permissions, custom role builder, 2FA, login history.'],
    ['key' => 'notifications_center', 'name' => 'Notifications Center', 'category' => 'core', 'icon' => 'heroicon-o-bell', 'dependencies' => ['core'], 'default' => true, 'tier' => 'core', 'always_on' => true, 'description' => 'In-app notification inbox, preferences, and activity feed.'],
    ['key' => 'audit_log', 'name' => 'Activity & Audit Log', 'category' => 'core', 'icon' => 'heroicon-o-clipboard-document-list', 'dependencies' => ['core'], 'default' => true, 'tier' => 'core', 'always_on' => true, 'description' => 'Tamper-evident audit trail of sensitive actions.'],
    ['key' => 'file_manager', 'name' => 'File Manager', 'category' => 'core', 'icon' => 'heroicon-o-folder', 'dependencies' => ['core'], 'default' => true, 'tier' => 'core', 'always_on' => true, 'description' => 'Secure media and document storage per business.'],
    ['key' => 'api_tokens', 'name' => 'API Tokens', 'category' => 'core', 'icon' => 'heroicon-o-key', 'dependencies' => ['core'], 'default' => true, 'tier' => 'core', 'always_on' => true, 'description' => 'Sanctum personal access tokens with scopes and rate limits.'],

    // A. ORDER & SHIPMENT
    ['key' => 'orders', 'name' => 'Orders & Shipments', 'category' => 'orders', 'icon' => 'heroicon-o-cube', 'dependencies' => ['core'], 'default' => true, 'tier' => 'starter', 'description' => 'Single/bulk create, CSV/Excel import-export, API intake, labels, barcodes/QR.'],
    ['key' => 'address_book', 'name' => 'Address Book & Geocoding', 'category' => 'orders', 'icon' => 'heroicon-o-map-pin', 'dependencies' => ['orders'], 'default' => true, 'tier' => 'starter', 'description' => 'Autocomplete, validation, geofences, duplicate detection, multi-language cleaning.'],
    ['key' => 'pickups', 'name' => 'Pickup Management', 'category' => 'orders', 'icon' => 'heroicon-o-truck', 'dependencies' => ['orders'], 'default' => true, 'tier' => 'starter', 'description' => 'Scheduled and recurring pickups with dedicated pickup routes.'],
    ['key' => 'returns', 'name' => 'Returns / Reverse Logistics', 'category' => 'orders', 'icon' => 'heroicon-o-arrow-uturn-left', 'dependencies' => ['orders'], 'default' => false, 'tier' => 'growth', 'description' => 'RMA return pickups, QC, and restock workflows.'],
    ['key' => 'multi_leg', 'name' => 'Multi-leg Hub-and-Spoke', 'category' => 'orders', 'icon' => 'heroicon-o-arrows-right-left', 'dependencies' => ['orders', 'hubs'], 'default' => false, 'tier' => 'growth', 'description' => 'First-mile, middle-mile, and last-mile leg orchestration.'],
    ['key' => 'cod', 'name' => 'Cash on Delivery', 'category' => 'orders', 'icon' => 'heroicon-o-banknotes', 'dependencies' => ['orders'], 'default' => true, 'tier' => 'starter', 'description' => 'COD collection, reconciliation, and merchant remittance.'],
    ['key' => 'pod', 'name' => 'Proof of Delivery', 'category' => 'orders', 'icon' => 'heroicon-o-camera', 'dependencies' => ['orders'], 'default' => true, 'tier' => 'starter', 'description' => 'Photo, signature, OTP, geotag, barcode scan, ID check.'],
    ['key' => 'attempts', 'name' => 'Delivery Attempts & Exceptions', 'category' => 'orders', 'icon' => 'heroicon-o-exclamation-triangle', 'dependencies' => ['orders'], 'default' => true, 'tier' => 'starter', 'description' => 'Failed reasons, auto re-attempt rules, reschedule, RTS.'],

    // B. ROUTE PLANNING
    ['key' => 'route_optimization', 'name' => 'Route Optimization Engine', 'category' => 'routing', 'icon' => 'heroicon-o-map', 'dependencies' => ['orders', 'fleet'], 'default' => true, 'tier' => 'starter', 'description' => 'VRP solver with capacity, time windows, skills, multi-depot, traffic-aware ETA.'],
    ['key' => 'dynamic_reopt', 'name' => 'Dynamic Re-optimization', 'category' => 'routing', 'icon' => 'heroicon-o-arrow-path', 'dependencies' => ['route_optimization'], 'default' => true, 'tier' => 'growth', 'description' => 'Insert/cancel orders, traffic delays, breakdowns; re-sequence in-flight routes.'],
    ['key' => 'recurring_routes', 'name' => 'Multi-day & Recurring Routes', 'category' => 'routing', 'icon' => 'heroicon-o-calendar-days', 'dependencies' => ['route_optimization'], 'default' => false, 'tier' => 'growth', 'description' => 'Templates, standing routes, and territory routes across days.'],
    ['key' => 'territories', 'name' => 'Territory & Zone Management', 'category' => 'routing', 'icon' => 'heroicon-o-globe-alt', 'dependencies' => ['orders'], 'default' => true, 'tier' => 'starter', 'description' => 'Draw polygons, assign drivers/hubs, zone pricing and auto-assign.'],
    ['key' => 'dispatch_board', 'name' => 'Dispatch Board', 'category' => 'routing', 'icon' => 'heroicon-o-squares-2x2', 'dependencies' => ['route_optimization'], 'default' => true, 'tier' => 'starter', 'description' => 'Drag-and-drop Kanban, map, and Gantt dispatch views.'],
    ['key' => 'scenario_simulator', 'name' => 'Scenario Simulator', 'category' => 'routing', 'icon' => 'heroicon-o-beaker', 'dependencies' => ['route_optimization'], 'default' => false, 'tier' => 'scale', 'description' => 'What-if side-by-side comparison of cost, time, km, and emissions.'],
    ['key' => 'auto_assignment', 'name' => 'Auto-Assignment Rules', 'category' => 'routing', 'icon' => 'heroicon-o-sparkles', 'dependencies' => ['dispatch_board'], 'default' => true, 'tier' => 'growth', 'description' => 'Nearest driver, load balance, skill match, rating, SLA priority.'],
    ['key' => 'batching_waves', 'name' => 'Batching & Wave Planning', 'category' => 'routing', 'icon' => 'heroicon-o-queue-list', 'dependencies' => ['route_optimization', 'hubs'], 'default' => false, 'tier' => 'growth', 'description' => 'Cut-off times, waves, consolidation, and cross-docking.'],
    ['key' => 'fleet_mix', 'name' => 'Fleet Mix Optimizer', 'category' => 'routing', 'icon' => 'heroicon-o-chart-pie', 'dependencies' => ['route_optimization', 'fleet'], 'default' => false, 'tier' => 'scale', 'description' => 'Recommend optimal vehicle types and fleet size.'],

    // C. LIVE OPS
    ['key' => 'live_tracking', 'name' => 'Live Tracking Map', 'category' => 'live', 'icon' => 'heroicon-o-signal', 'dependencies' => ['route_optimization'], 'default' => true, 'tier' => 'starter', 'description' => 'Real-time driver positions, geofence alerts, playback/history.'],
    ['key' => 'driver_pwa', 'name' => 'Driver Mobile PWA', 'category' => 'live', 'icon' => 'heroicon-o-device-phone-mobile', 'dependencies' => ['live_tracking', 'pod'], 'default' => true, 'tier' => 'starter', 'description' => 'Offline-first stop list, GPS pings, nav deep links, POD, SOS.'],
    ['key' => 'customer_tracking', 'name' => 'Customer Tracking Portal', 'category' => 'live', 'icon' => 'heroicon-o-eye', 'dependencies' => ['orders', 'live_tracking'], 'default' => true, 'tier' => 'starter', 'description' => 'Public tracking by code/phone with branded live ETA map.'],
    ['key' => 'eta_prediction', 'name' => 'ETA Prediction & Delay Alerts', 'category' => 'live', 'icon' => 'heroicon-o-clock', 'dependencies' => ['live_tracking'], 'default' => true, 'tier' => 'growth', 'description' => 'Historical + traffic + AI-assisted ETA with delay notifications.'],
    ['key' => 'notifications_hub', 'name' => 'Omnichannel Notifications Hub', 'category' => 'live', 'icon' => 'heroicon-o-chat-bubble-left-right', 'dependencies' => ['notifications_center'], 'default' => true, 'tier' => 'growth', 'description' => 'SMS, WhatsApp, Email, Push, Voice; templates; BD SMS gateways.'],
    ['key' => 'delivery_preferences', 'name' => 'Customer Delivery Preferences', 'category' => 'live', 'icon' => 'heroicon-o-adjustments-horizontal', 'dependencies' => ['customer_tracking'], 'default' => false, 'tier' => 'growth', 'description' => 'Time slots, safe-place, neighbor, locker, reschedule, redirect.'],
    ['key' => 'chat', 'name' => 'Two-way Chat', 'category' => 'live', 'icon' => 'heroicon-o-chat-bubble-oval-left-ellipsis', 'dependencies' => ['live_tracking'], 'default' => false, 'tier' => 'growth', 'description' => 'Driver↔customer (masked) and dispatcher↔driver messaging.'],
    ['key' => 'exception_center', 'name' => 'Exception Management Center', 'category' => 'live', 'icon' => 'heroicon-o-shield-exclamation', 'dependencies' => ['attempts'], 'default' => true, 'tier' => 'growth', 'description' => 'SLA breach, failed, damaged, lost — workflow and escalation.'],

    // D. FLEET
    ['key' => 'fleet', 'name' => 'Vehicle Management', 'category' => 'fleet', 'icon' => 'heroicon-o-truck', 'dependencies' => ['core'], 'default' => true, 'tier' => 'starter', 'description' => 'Types, capacity, documents/expiry alerts, assignment history.'],
    ['key' => 'maintenance', 'name' => 'Maintenance & Inspections', 'category' => 'fleet', 'icon' => 'heroicon-o-wrench-screwdriver', 'dependencies' => ['fleet'], 'default' => false, 'tier' => 'growth', 'description' => 'Schedules, work orders, DVIR checklists, parts.'],
    ['key' => 'fuel', 'name' => 'Fuel & Expense Tracking', 'category' => 'fleet', 'icon' => 'heroicon-o-fire', 'dependencies' => ['fleet'], 'default' => false, 'tier' => 'growth', 'description' => 'Fuel cards, mileage, anomaly detection.'],
    ['key' => 'telematics', 'name' => 'Telematics / GPS Devices', 'category' => 'fleet', 'icon' => 'heroicon-o-cpu-chip', 'dependencies' => ['fleet', 'live_tracking'], 'default' => false, 'tier' => 'scale', 'description' => 'Generic webhook/MQTT/HTTP ingestion drivers.'],
    ['key' => 'ev_management', 'name' => 'Electric Vehicle Management', 'category' => 'fleet', 'icon' => 'heroicon-o-bolt', 'dependencies' => ['fleet', 'route_optimization'], 'default' => false, 'tier' => 'scale', 'description' => 'Charge planning and range-aware routing.'],
    ['key' => 'assets', 'name' => 'Equipment & Asset Tracking', 'category' => 'fleet', 'icon' => 'heroicon-o-archive-box', 'dependencies' => ['fleet'], 'default' => false, 'tier' => 'growth', 'description' => 'Bags, crates, scanners, cold-chain boxes.'],
    ['key' => 'cold_chain', 'name' => 'Cold Chain Monitoring', 'category' => 'fleet', 'icon' => 'heroicon-o-thermometer', 'dependencies' => ['assets'], 'default' => false, 'tier' => 'scale', 'description' => 'Sensor readings, alerts, compliance reports.'],

    // E. WAREHOUSE
    ['key' => 'hubs', 'name' => 'Hub & Warehouse Management', 'category' => 'warehouse', 'icon' => 'heroicon-o-building-storefront', 'dependencies' => ['core'], 'default' => true, 'tier' => 'starter', 'description' => 'Hubs, bins, inbound scanning, sorting, load-out, manifests.'],
    ['key' => 'inventory', 'name' => 'Light WMS Inventory', 'category' => 'warehouse', 'icon' => 'heroicon-o-squares-plus', 'dependencies' => ['hubs'], 'default' => false, 'tier' => 'growth', 'description' => 'Stock per merchant, pick/pack, cycle count.'],
    ['key' => 'lockers', 'name' => 'Lockers / PUDO Points', 'category' => 'warehouse', 'icon' => 'heroicon-o-lock-closed', 'dependencies' => ['hubs', 'orders'], 'default' => false, 'tier' => 'growth', 'description' => 'Locker/agent points, capacity, OTP pickup.'],
    ['key' => 'cross_dock', 'name' => 'Cross-dock & Transfer Manifests', 'category' => 'warehouse', 'icon' => 'heroicon-o-arrows-up-down', 'dependencies' => ['hubs', 'multi_leg'], 'default' => false, 'tier' => 'growth', 'description' => 'Transfer manifests between hubs and partners.'],

    // F. PEOPLE
    ['key' => 'drivers', 'name' => 'Driver Management', 'category' => 'people', 'icon' => 'heroicon-o-identification', 'dependencies' => ['users_roles'], 'default' => true, 'tier' => 'starter', 'description' => 'Onboarding, documents, ratings, scorecards, gamification.'],
    ['key' => 'attendance', 'name' => 'Attendance & Shifts', 'category' => 'people', 'icon' => 'heroicon-o-finger-print', 'dependencies' => ['drivers'], 'default' => true, 'tier' => 'growth', 'description' => 'Biometric/RFID/app/geofenced check-in via Attendance Hub.'],
    ['key' => 'payroll', 'name' => 'Payroll & Driver Payouts', 'category' => 'people', 'icon' => 'heroicon-o-currency-dollar', 'dependencies' => ['drivers'], 'default' => false, 'tier' => 'growth', 'description' => 'Per-delivery/km/hour/commission payouts with Unicode PDF payslips.'],
    ['key' => 'hr_lite', 'name' => 'HR-lite', 'category' => 'people', 'icon' => 'heroicon-o-briefcase', 'dependencies' => ['users_roles'], 'default' => false, 'tier' => 'growth', 'description' => 'Staff directory, contracts, documents, announcements.'],
    ['key' => 'gig_marketplace', 'name' => 'Gig Driver Marketplace', 'category' => 'people', 'icon' => 'heroicon-o-megaphone', 'dependencies' => ['drivers', 'dispatch_board'], 'default' => false, 'tier' => 'scale', 'description' => 'Open jobs, bidding, instant payout requests.'],
    ['key' => 'subcontractors', 'name' => 'Subcontractor / Carrier Management', 'category' => 'people', 'icon' => 'heroicon-o-building-library', 'dependencies' => ['orders'], 'default' => false, 'tier' => 'scale', 'description' => 'Rate cards, auto-routing to partner carriers, partner API.'],

    // G. FINANCE
    ['key' => 'pricing', 'name' => 'Pricing Engine / Rate Cards', 'category' => 'finance', 'icon' => 'heroicon-o-tag', 'dependencies' => ['orders'], 'default' => true, 'tier' => 'starter', 'description' => 'Zone/distance/weight/volume/service/surge/contract pricing.'],
    ['key' => 'invoicing', 'name' => 'Invoicing & Billing', 'category' => 'finance', 'icon' => 'heroicon-o-document-text', 'dependencies' => ['pricing'], 'default' => true, 'tier' => 'starter', 'description' => 'Shipment/periodic/consolidated invoices, taxes, multi-currency Unicode PDFs.'],
    ['key' => 'payments', 'name' => 'Payments', 'category' => 'finance', 'icon' => 'heroicon-o-credit-card', 'dependencies' => ['invoicing'], 'default' => true, 'tier' => 'growth', 'description' => 'Stripe, SSLCommerz, bKash/Nagad, PayPal, wallets, prepaid balance.'],
    ['key' => 'cod_settlement', 'name' => 'COD Settlement & Remittance', 'category' => 'finance', 'icon' => 'heroicon-o-scale', 'dependencies' => ['cod', 'invoicing'], 'default' => true, 'tier' => 'growth', 'description' => 'COD reconciliation and merchant remittance batches.'],
    ['key' => 'expenses', 'name' => 'Expenses & Accounting-lite', 'category' => 'finance', 'icon' => 'heroicon-o-calculator', 'dependencies' => ['core'], 'default' => false, 'tier' => 'growth', 'description' => 'Ledger, P&L, cash book, accounting exports.'],
    ['key' => 'profitability', 'name' => 'Profitability Analytics', 'category' => 'finance', 'icon' => 'heroicon-o-chart-bar', 'dependencies' => ['invoicing', 'route_optimization'], 'default' => false, 'tier' => 'scale', 'description' => 'Cost per delivery/km/route/customer/zone.'],
    ['key' => 'saas_billing', 'name' => 'Subscriptions & SaaS Billing', 'category' => 'finance', 'icon' => 'heroicon-o-receipt-percent', 'dependencies' => ['core'], 'default' => true, 'tier' => 'platform', 'description' => 'Plans, per-module pricing, trials, metering, dunning (Super Admin).'],

    // H. CUSTOMER & SALES
    ['key' => 'crm', 'name' => 'CRM', 'category' => 'sales', 'icon' => 'heroicon-o-user-group', 'dependencies' => ['core'], 'default' => false, 'tier' => 'growth', 'description' => 'Merchants/leads, pipeline, quotes, contracts, follow-ups.'],
    ['key' => 'lead_gen', 'name' => 'Lead Generation via Scraping', 'category' => 'sales', 'icon' => 'heroicon-o-magnifying-glass', 'dependencies' => ['crm'], 'default' => false, 'tier' => 'scale', 'description' => 'AI Catalog scrapes public directories into lead lists with compliance toggles.'],
    ['key' => 'ticketing', 'name' => 'Support Ticketing & Helpdesk', 'category' => 'sales', 'icon' => 'heroicon-o-ticket', 'dependencies' => ['orders'], 'default' => true, 'tier' => 'growth', 'description' => 'SLA, canned replies, macros, omnichannel tickets.'],
    ['key' => 'feedback', 'name' => 'Feedback, Ratings & NPS', 'category' => 'sales', 'icon' => 'heroicon-o-star', 'dependencies' => ['orders'], 'default' => true, 'tier' => 'starter', 'description' => 'Post-delivery surveys, driver rating, review moderation.'],
    ['key' => 'loyalty', 'name' => 'Loyalty, Tips & Referral', 'category' => 'sales', 'icon' => 'heroicon-o-gift', 'dependencies' => ['orders'], 'default' => false, 'tier' => 'growth', 'description' => 'Loyalty points, tipping, and referral programs.'],
    ['key' => 'merchant_portal', 'name' => 'Merchant Portal & E-commerce Plugins', 'category' => 'sales', 'icon' => 'heroicon-o-shopping-bag', 'dependencies' => ['orders', 'api_tokens'], 'default' => true, 'tier' => 'growth', 'description' => 'Shopify/Woo/Magento connectors, webhooks, merchant self-service.'],

    // I. AI
    ['key' => 'ai_copilot', 'name' => 'AI Dispatcher Copilot', 'category' => 'ai', 'icon' => 'heroicon-o-cpu-chip', 'dependencies' => ['dispatch_board'], 'default' => false, 'tier' => 'scale', 'description' => 'Chat with ops data, suggestions, route decision explanations via AI Hub.'],
    ['key' => 'demand_forecast', 'name' => 'Demand Forecasting', 'category' => 'ai', 'icon' => 'heroicon-o-presentation-chart-line', 'dependencies' => ['orders'], 'default' => false, 'tier' => 'scale', 'description' => 'Capacity planning from historical demand patterns.'],
    ['key' => 'ai_address', 'name' => 'AI Address Cleaner & Translation', 'category' => 'ai', 'icon' => 'heroicon-o-language', 'dependencies' => ['address_book'], 'default' => false, 'tier' => 'growth', 'description' => 'Any-language address cleaning and translation via AI Hub.'],
    ['key' => 'smart_eta', 'name' => 'Smart ETA & Delay Prediction', 'category' => 'ai', 'icon' => 'heroicon-o-light-bulb', 'dependencies' => ['eta_prediction'], 'default' => false, 'tier' => 'scale', 'description' => 'AI-enhanced ETA models beyond rule-based prediction.'],
    ['key' => 'anomaly_fraud', 'name' => 'Anomaly & Fraud Detection', 'category' => 'ai', 'icon' => 'heroicon-o-shield-check', 'dependencies' => ['pod', 'cod'], 'default' => false, 'tier' => 'scale', 'description' => 'Fake POD, fuel theft, COD mismatch, GPS spoofing detection.'],
    ['key' => 'ai_support_bot', 'name' => 'AI Customer Support Bot', 'category' => 'ai', 'icon' => 'heroicon-o-chat-bubble-bottom-center-text', 'dependencies' => ['ticketing', 'customer_tracking'], 'default' => false, 'tier' => 'scale', 'description' => 'Multi-language tracking, rescheduling, and FAQ bot.'],
    ['key' => 'nl_reports', 'name' => 'Natural-Language Report Builder', 'category' => 'ai', 'icon' => 'heroicon-o-document-magnifying-glass', 'dependencies' => ['reports'], 'default' => false, 'tier' => 'scale', 'description' => 'Ask for reports in plain language; get charts and exports.'],
    ['key' => 'automation', 'name' => 'Workflow Automation Builder', 'category' => 'ai', 'icon' => 'heroicon-o-puzzle-piece', 'dependencies' => ['notifications_hub'], 'default' => false, 'tier' => 'growth', 'description' => 'No-code triggers → conditions → actions.'],

    // J. ANALYTICS
    ['key' => 'dashboards', 'name' => 'Role Dashboards', 'category' => 'analytics', 'icon' => 'heroicon-o-home-modern', 'dependencies' => ['core'], 'default' => true, 'tier' => 'starter', 'description' => 'Per-role KPI dashboards via Admin Panel widgets.'],
    ['key' => 'reports', 'name' => 'Reports Center', 'category' => 'analytics', 'icon' => 'heroicon-o-document-chart-bar', 'dependencies' => ['dashboards'], 'default' => true, 'tier' => 'growth', 'description' => 'OTD, FAS, cost/stop, productivity; schedule; PDF/Excel/CSV.'],
    ['key' => 'sustainability', 'name' => 'Sustainability / Carbon Tracking', 'category' => 'analytics', 'icon' => 'heroicon-o-leaf', 'dependencies' => ['route_optimization'], 'default' => false, 'tier' => 'growth', 'description' => 'CO₂ per route/delivery, green reports, EV share.'],
    ['key' => 'compliance', 'name' => 'Compliance & Documents', 'category' => 'analytics', 'icon' => 'heroicon-o-document-check', 'dependencies' => ['drivers', 'fleet'], 'default' => false, 'tier' => 'growth', 'description' => 'Licences, permits, insurance, HOS rules, audit trail.'],
    ['key' => 'data_export', 'name' => 'Data Warehouse Export & BI', 'category' => 'analytics', 'icon' => 'heroicon-o-circle-stack', 'dependencies' => ['reports'], 'default' => false, 'tier' => 'scale', 'description' => 'Scheduled warehouse exports and BI connectors.'],
    ['key' => 'public_api', 'name' => 'Public API & Webhooks', 'category' => 'analytics', 'icon' => 'heroicon-o-code-bracket', 'dependencies' => ['api_tokens', 'orders'], 'default' => true, 'tier' => 'growth', 'description' => 'REST v1 OpenAPI, scopes, rate limits, webhook retries.'],

    // K. PLATFORM
    ['key' => 'white_label', 'name' => 'White-label & Custom Domains', 'category' => 'platform', 'icon' => 'heroicon-o-paint-brush', 'dependencies' => ['core'], 'default' => false, 'tier' => 'scale', 'description' => 'Per-business branding and custom domains.'],
    ['key' => 'i18n', 'name' => 'Multi-language & RTL', 'category' => 'platform', 'icon' => 'heroicon-o-language', 'dependencies' => ['core'], 'default' => true, 'tier' => 'starter', 'description' => 'EN/BN/AR/HI/ES/FR with RTL and universal slugs.'],
    ['key' => 'multi_currency', 'name' => 'Multi-currency & Timezones', 'category' => 'platform', 'icon' => 'heroicon-o-globe-europe-africa', 'dependencies' => ['core'], 'default' => true, 'tier' => 'starter', 'description' => 'Per-business currency and timezone handling.'],
    ['key' => 'franchise', 'name' => 'Franchise / Multi-branch', 'category' => 'platform', 'icon' => 'heroicon-o-share', 'dependencies' => ['core'], 'default' => false, 'tier' => 'scale', 'description' => 'Sub-business franchise network with commissions.'],
    ['key' => 'gdpr', 'name' => 'Backup, Import/Export & GDPR', 'category' => 'platform', 'icon' => 'heroicon-o-lock-open', 'dependencies' => ['core'], 'default' => true, 'tier' => 'growth', 'description' => 'Data retention, export, erasure, and backup tools.'],
    ['key' => 'integrations', 'name' => 'Integrations Hub', 'category' => 'platform', 'icon' => 'heroicon-o-puzzle-piece', 'dependencies' => ['core'], 'default' => true, 'tier' => 'growth', 'description' => 'Maps, routing engines, Twilio, WhatsApp, Firebase, S3, Slack, Zapier.'],
    ['key' => 'help_center', 'name' => 'Help Center & Onboarding Tours', 'category' => 'platform', 'icon' => 'heroicon-o-question-mark-circle', 'dependencies' => ['core'], 'default' => true, 'tier' => 'starter', 'description' => 'Knowledge base articles and in-app guided tours.'],
    ['key' => 'system_health', 'name' => 'System Health', 'category' => 'platform', 'icon' => 'heroicon-o-heart', 'dependencies' => ['core'], 'default' => true, 'tier' => 'platform', 'description' => 'Horizon/queue status, scheduler monitor, error log, Drutogoti panel.'],
];

$presets = [
    'courier' => [
        'name' => 'Courier / Parcel Company',
        'modules' => ['orders', 'address_book', 'pickups', 'returns', 'cod', 'pod', 'attempts', 'route_optimization', 'dynamic_reopt', 'territories', 'dispatch_board', 'auto_assignment', 'live_tracking', 'driver_pwa', 'customer_tracking', 'eta_prediction', 'notifications_hub', 'exception_center', 'fleet', 'hubs', 'drivers', 'attendance', 'pricing', 'invoicing', 'payments', 'cod_settlement', 'ticketing', 'feedback', 'merchant_portal', 'dashboards', 'reports', 'public_api', 'i18n', 'multi_currency', 'integrations', 'help_center'],
    ],
    'food' => [
        'name' => 'Food Delivery',
        'modules' => ['orders', 'address_book', 'pod', 'attempts', 'route_optimization', 'dynamic_reopt', 'dispatch_board', 'auto_assignment', 'live_tracking', 'driver_pwa', 'customer_tracking', 'eta_prediction', 'notifications_hub', 'chat', 'fleet', 'drivers', 'attendance', 'gig_marketplace', 'pricing', 'payments', 'feedback', 'loyalty', 'dashboards', 'i18n'],
    ],
    'grocery' => [
        'name' => 'Grocery / Quick-Commerce',
        'modules' => ['orders', 'address_book', 'pickups', 'pod', 'attempts', 'route_optimization', 'dynamic_reopt', 'batching_waves', 'dispatch_board', 'live_tracking', 'driver_pwa', 'customer_tracking', 'eta_prediction', 'notifications_hub', 'delivery_preferences', 'fleet', 'cold_chain', 'hubs', 'inventory', 'lockers', 'drivers', 'attendance', 'pricing', 'invoicing', 'payments', 'feedback', 'ai_copilot', 'demand_forecast', 'dashboards', 'reports', 'i18n'],
    ],
    'ecommerce' => [
        'name' => 'E-commerce Fulfillment',
        'modules' => ['orders', 'address_book', 'pickups', 'returns', 'multi_leg', 'cod', 'pod', 'attempts', 'route_optimization', 'territories', 'dispatch_board', 'batching_waves', 'live_tracking', 'customer_tracking', 'notifications_hub', 'hubs', 'inventory', 'cross_dock', 'drivers', 'pricing', 'invoicing', 'payments', 'cod_settlement', 'merchant_portal', 'public_api', 'dashboards', 'reports'],
    ],
    '3pl' => [
        'name' => '3PL / Warehouse',
        'modules' => ['orders', 'address_book', 'multi_leg', 'pod', 'route_optimization', 'recurring_routes', 'territories', 'dispatch_board', 'batching_waves', 'fleet_mix', 'live_tracking', 'hubs', 'inventory', 'cross_dock', 'fleet', 'drivers', 'subcontractors', 'pricing', 'invoicing', 'expenses', 'profitability', 'crm', 'dashboards', 'reports', 'public_api'],
    ],
    'pharmacy' => [
        'name' => 'Pharmacy Delivery',
        'modules' => ['orders', 'address_book', 'pod', 'attempts', 'route_optimization', 'dispatch_board', 'live_tracking', 'driver_pwa', 'customer_tracking', 'notifications_hub', 'delivery_preferences', 'fleet', 'cold_chain', 'drivers', 'compliance', 'pricing', 'invoicing', 'ticketing', 'feedback', 'dashboards'],
    ],
    'chauffeur' => [
        'name' => 'Chauffeur / Fleet Service',
        'modules' => ['orders', 'route_optimization', 'recurring_routes', 'dispatch_board', 'live_tracking', 'driver_pwa', 'fleet', 'maintenance', 'fuel', 'telematics', 'drivers', 'attendance', 'payroll', 'pricing', 'invoicing', 'payments', 'expenses', 'dashboards', 'reports', 'compliance'],
    ],
    'field_service' => [
        'name' => 'Field-Service Dispatch',
        'modules' => ['orders', 'address_book', 'route_optimization', 'dynamic_reopt', 'dispatch_board', 'auto_assignment', 'live_tracking', 'driver_pwa', 'eta_prediction', 'chat', 'exception_center', 'fleet', 'drivers', 'attendance', 'payroll', 'pricing', 'ticketing', 'feedback', 'automation', 'dashboards'],
    ],
    'freight' => [
        'name' => 'Freight / Last-Mile Carrier',
        'modules' => ['orders', 'address_book', 'pickups', 'multi_leg', 'cod', 'pod', 'attempts', 'route_optimization', 'territories', 'dispatch_board', 'fleet_mix', 'live_tracking', 'fleet', 'hubs', 'cross_dock', 'drivers', 'subcontractors', 'pricing', 'invoicing', 'cod_settlement', 'sustainability', 'dashboards', 'reports', 'public_api'],
    ],
    'franchise' => [
        'name' => 'Franchise Network',
        'modules' => ['orders', 'address_book', 'cod', 'pod', 'route_optimization', 'territories', 'dispatch_board', 'live_tracking', 'customer_tracking', 'hubs', 'drivers', 'pricing', 'invoicing', 'cod_settlement', 'crm', 'franchise', 'white_label', 'dashboards', 'reports', 'saas_billing'],
    ],
    'custom' => [
        'name' => 'Custom',
        'modules' => ['orders', 'address_book', 'pod', 'route_optimization', 'dispatch_board', 'live_tracking', 'fleet', 'drivers', 'pricing', 'dashboards'],
    ],
];

// ---- config/modules.php ----
$modulesExport = var_export($modules, true);
$presetsExport = var_export($presets, true);
write("{$root}/config/modules.php", <<<PHP
<?php

declare(strict_types=1);

/**
 * Master module registry for Last-Mile Delivery Optimizer.
 * Public website feature pages and the Module Marketplace both read from here.
 */

return [
    'cache_ttl' => (int) env('MODULES_CACHE_TTL', 300),
    'cache_key' => 'lmdo.modules',

    'categories' => [
        'core' => ['label' => 'Core Platform', 'letter' => '—'],
        'orders' => ['label' => 'Order & Shipment Management', 'letter' => 'A'],
        'routing' => ['label' => 'Route Planning & Optimization', 'letter' => 'B'],
        'live' => ['label' => 'Live Operations & Tracking', 'letter' => 'C'],
        'fleet' => ['label' => 'Fleet & Assets', 'letter' => 'D'],
        'warehouse' => ['label' => 'Warehouse / Hub', 'letter' => 'E'],
        'people' => ['label' => 'People & Workforce', 'letter' => 'F'],
        'finance' => ['label' => 'Finance & Billing', 'letter' => 'G'],
        'sales' => ['label' => 'Customer & Sales', 'letter' => 'H'],
        'ai' => ['label' => 'AI & Automation', 'letter' => 'I'],
        'analytics' => ['label' => 'Analytics, Reporting, Compliance', 'letter' => 'J'],
        'platform' => ['label' => 'Platform', 'letter' => 'K'],
    ],

    'pricing_tiers' => [
        'core' => ['label' => 'Included', 'monthly' => 0],
        'starter' => ['label' => 'Starter', 'monthly' => 29],
        'growth' => ['label' => 'Growth', 'monthly' => 79],
        'scale' => ['label' => 'Scale', 'monthly' => 149],
        'platform' => ['label' => 'Platform', 'monthly' => 0],
    ],

    'presets' => {$presetsExport},

    'registry' => {$modulesExport},
];

PHP);

// ---- ModuleManager ----
write("{$root}/app/Support/Modules/ModuleManager.php", <<<'PHP'
<?php

declare(strict_types=1);

namespace App\Support\Modules;

use App\Models\Business;
use App\Models\BusinessModule;
use App\Models\Module;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

final class ModuleManager
{
    public function registry(): Collection
    {
        return collect(config('modules.registry', []))->keyBy('key');
    }

    public function categories(): array
    {
        return config('modules.categories', []);
    }

    public function presets(): array
    {
        return config('modules.presets', []);
    }

    public function find(string $key): ?array
    {
        return $this->registry()->get($key);
    }

    public function isAlwaysOn(string $key): bool
    {
        return (bool) ($this->find($key)['always_on'] ?? false);
    }

    public function isEnabled(string $module, ?Business $business = null): bool
    {
        if ($this->isAlwaysOn($module)) {
            return true;
        }

        $business ??= app(TenantContext::class)->business();

        if ($business === null) {
            return (bool) ($this->find($module)['default'] ?? false);
        }

        return (bool) $this->enabledMap($business)[$module] ?? false;
    }

    /**
     * @return array<string, bool>
     */
    public function enabledMap(Business $business): array
    {
        $ttl = (int) config('modules.cache_ttl', 300);
        $key = config('modules.cache_key', 'lmdo.modules').".{$business->id}";

        return Cache::remember($key, $ttl, function () use ($business): array {
            $defaults = $this->registry()->mapWithKeys(
                fn (array $m): array => [$m['key'] => (bool) ($m['default'] ?? false) || (bool) ($m['always_on'] ?? false)]
            )->all();

            $overrides = BusinessModule::query()
                ->where('business_id', $business->id)
                ->get(['module_key', 'is_enabled'])
                ->mapWithKeys(fn (BusinessModule $row): array => [$row->module_key => (bool) $row->is_enabled])
                ->all();

            foreach ($this->registry() as $mod) {
                if ($mod['always_on'] ?? false) {
                    $overrides[$mod['key']] = true;
                }
            }

            return array_merge($defaults, $overrides);
        });
    }

    public function forgetCache(?Business $business = null): void
    {
        if ($business) {
            Cache::forget(config('modules.cache_key', 'lmdo.modules').".{$business->id}");

            return;
        }

        Business::query()->pluck('id')->each(
            fn ($id) => Cache::forget(config('modules.cache_key', 'lmdo.modules').".{$id}")
        );
    }

    /**
     * @return list<string>
     */
    public function unmetDependencies(string $module, Business $business): array
    {
        $deps = $this->find($module)['dependencies'] ?? [];
        $enabled = $this->enabledMap($business);

        return array_values(array_filter(
            $deps,
            fn (string $dep): bool => ! ($enabled[$dep] ?? false)
        ));
    }

    /**
     * @return list<string>
     */
    public function dependents(string $module, Business $business): array
    {
        $enabled = $this->enabledMap($business);

        return $this->registry()
            ->filter(function (array $m) use ($module, $enabled): bool {
                return ($enabled[$m['key']] ?? false)
                    && in_array($module, $m['dependencies'] ?? [], true);
            })
            ->keys()
            ->values()
            ->all();
    }

    public function enable(string $module, Business $business, array $settings = []): BusinessModule
    {
        if ($this->find($module) === null) {
            throw new InvalidArgumentException("Unknown module [{$module}].");
        }

        $unmet = $this->unmetDependencies($module, $business);
        if ($unmet !== []) {
            throw new RuntimeException(
                "Cannot enable [{$module}]; unmet dependencies: ".implode(', ', $unmet)
            );
        }

        return DB::transaction(function () use ($module, $business, $settings): BusinessModule {
            $row = BusinessModule::query()->updateOrCreate(
                ['business_id' => $business->id, 'module_key' => $module],
                [
                    'is_enabled' => true,
                    'settings' => $settings !== [] ? $settings : null,
                    'enabled_at' => now(),
                    'disabled_at' => null,
                ]
            );

            $this->forgetCache($business);

            return $row;
        });
    }

    public function disable(string $module, Business $business): BusinessModule
    {
        if ($this->isAlwaysOn($module)) {
            throw new RuntimeException("Module [{$module}] is always on and cannot be disabled.");
        }

        $deps = $this->dependents($module, $business);
        if ($deps !== []) {
            throw new RuntimeException(
                "Cannot disable [{$module}]; required by: ".implode(', ', $deps)
            );
        }

        return DB::transaction(function () use ($module, $business): BusinessModule {
            $row = BusinessModule::query()->updateOrCreate(
                ['business_id' => $business->id, 'module_key' => $module],
                [
                    'is_enabled' => false,
                    'disabled_at' => now(),
                ]
            );

            $this->forgetCache($business);

            return $row;
        });
    }

    /**
     * @param  list<string>  $moduleKeys
     */
    public function applyPreset(string $presetKey, Business $business): void
    {
        $preset = $this->presets()[$presetKey] ?? null;
        if ($preset === null) {
            throw new InvalidArgumentException("Unknown preset [{$presetKey}].");
        }

        DB::transaction(function () use ($preset, $business, $presetKey): void {
            $business->update(['business_type' => $presetKey]);

            $wanted = array_unique(array_merge(
                $this->registry()->filter(fn ($m) => ($m['always_on'] ?? false) || ($m['default'] ?? false))->pluck('key')->all(),
                $preset['modules']
            ));

            // Disable non-wanted first (reverse dependency order approximation)
            foreach ($this->registry()->keys()->reverse() as $key) {
                if (! in_array($key, $wanted, true) && ! $this->isAlwaysOn($key)) {
                    try {
                        $this->disable($key, $business);
                    } catch (RuntimeException) {
                        // leave enabled if dependents still want it; second pass below
                    }
                }
            }

            // Topological-ish enable: repeat until stable
            $remaining = $wanted;
            $guard = 0;
            while ($remaining !== [] && $guard++ < 50) {
                $progress = false;
                foreach ($remaining as $i => $key) {
                    if ($this->unmetDependencies($key, $business) === []) {
                        $this->enable($key, $business);
                        unset($remaining[$i]);
                        $progress = true;
                    }
                }
                $remaining = array_values($remaining);
                if (! $progress) {
                    break;
                }
            }

            $this->forgetCache($business);
        });
    }

    public function syncCatalogToDatabase(): int
    {
        $count = 0;
        foreach ($this->registry() as $mod) {
            Module::query()->updateOrCreate(
                ['key' => $mod['key']],
                [
                    'name' => $mod['name'],
                    'description' => $mod['description'] ?? null,
                    'icon' => $mod['icon'] ?? null,
                    'category' => $mod['category'],
                    'dependencies' => $mod['dependencies'] ?? [],
                    'version' => $mod['version'] ?? '1.0.0',
                    'default_enabled' => (bool) ($mod['default'] ?? false),
                    'always_on' => (bool) ($mod['always_on'] ?? false),
                    'pricing_tier' => $mod['tier'] ?? 'starter',
                    'settings_schema' => $mod['settings_schema'] ?? [],
                    'what_you_lose' => $mod['what_you_lose'] ?? null,
                    'is_active' => true,
                ]
            );
            $count++;
        }

        return $count;
    }
}
PHP);

write("{$root}/app/Support/Tenancy/TenantContext.php", <<<'PHP'
<?php

declare(strict_types=1);

namespace App\Support\Tenancy;

use App\Models\Business;
use App\Models\User;

final class TenantContext
{
    private ?Business $business = null;

    public function set(?Business $business): void
    {
        $this->business = $business;
    }

    public function business(): ?Business
    {
        return $this->business;
    }

    public function id(): ?int
    {
        return $this->business?->id;
    }

    public function setFromUser(?User $user): void
    {
        if ($user === null) {
            $this->business = null;

            return;
        }

        if ($user->isSuperAdmin()) {
            // Super admin may impersonate; otherwise no tenant scope by default.
            if ($user->impersonated_business_id) {
                $this->business = Business::query()->find($user->impersonated_business_id);
            }

            return;
        }

        $this->business = $user->business;
    }

    public function check(): bool
    {
        return $this->business !== null;
    }
}
PHP);

write("{$root}/app/Support/Tenancy/BelongsToBusiness.php", <<<'PHP'
<?php

declare(strict_types=1);

namespace App\Support\Tenancy;

use App\Models\Business;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Model
 */
trait BelongsToBusiness
{
    public static function bootBelongsToBusiness(): void
    {
        static::creating(function (Model $model): void {
            if (! $model->getAttribute('business_id')) {
                $id = app(TenantContext::class)->id();
                if ($id) {
                    $model->setAttribute('business_id', $id);
                }
            }
        });

        static::addGlobalScope('business', function (Builder $builder): void {
            $context = app(TenantContext::class);
            $user = auth()->user();

            if ($user && method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin() && ! $context->check()) {
                return;
            }

            if ($context->check()) {
                $builder->where(
                    $builder->getModel()->getTable().'.business_id',
                    $context->id()
                );
            }
        });
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
PHP);

write("{$root}/app/Support/Enums/UserRole.php", <<<'PHP'
<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case BusinessOwner = 'business_owner';
    case OperationsManager = 'operations_manager';
    case HubManager = 'hub_manager';
    case Driver = 'driver';
    case FleetManager = 'fleet_manager';
    case Finance = 'finance';
    case SupportAgent = 'support_agent';
    case Merchant = 'merchant';
    case Customer = 'customer';
    case FranchisePartner = 'franchise_partner';
    case Auditor = 'auditor';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::BusinessOwner => 'Business Owner / Admin',
            self::OperationsManager => 'Operations Manager / Dispatcher',
            self::HubManager => 'Hub / Warehouse Manager',
            self::Driver => 'Driver / Rider',
            self::FleetManager => 'Fleet Manager',
            self::Finance => 'Finance / Accountant',
            self::SupportAgent => 'Customer Support Agent',
            self::Merchant => 'Merchant / Shipper',
            self::Customer => 'End Customer / Recipient',
            self::FranchisePartner => 'Franchise / Agent Partner',
            self::Auditor => 'Auditor / Read-only',
        };
    }

    public function panelId(): string
    {
        return match ($this) {
            self::SuperAdmin => 'super-admin',
            self::BusinessOwner => 'business',
            self::OperationsManager => 'operations',
            self::HubManager => 'hub',
            self::Driver => 'driver',
            self::FleetManager => 'fleet',
            self::Finance => 'finance',
            self::SupportAgent => 'support',
            self::Merchant => 'merchant',
            self::Customer => 'customer',
            self::FranchisePartner => 'franchise',
            self::Auditor => 'auditor',
        };
    }

    /**
     * @return list<self>
     */
    public static function businessRoles(): array
    {
        return array_values(array_filter(
            self::cases(),
            fn (self $role): bool => $role !== self::SuperAdmin
        ));
    }
}
PHP);

echo "Phase 1 core files generated. Modules: ".count($modules)." Presets: ".count($presets)."\n";
PHP
