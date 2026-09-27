<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Produk Online - NURMART</title>
    <meta name="description" content="Katalog belanja dan pemesanan online Toko Kelontong NurMart. Praktis, cepat, dan selalu update dengan stok terkini.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Canvas Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>

    <style>
        :root {
            --primary: #059669;
            --primary-light: #10b981;
            --primary-dark: #047857;
            --primary-bg: #ecfdf5;
            --accent: #f59e0b;
            --accent-light: #fef3c7;
            --danger: #ef4444;
            --danger-bg: #fee2e2;
            --dark-bg: #0f172a;
            --dark-card: #1e293b;
            --light-bg: #f8fafc;
            --light-card: #ffffff;
            --light-border: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --radius-lg: 18px;
            --radius-md: 12px;
            --radius-sm: 8px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 12px 28px rgba(0,0,0,0.12);
            --shadow-glow: 0 0 24px rgba(16, 185, 129, 0.28);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--light-bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-bottom: 90px;
        }

        /* Top Header */
        .header {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--light-border);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-main);
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--primary), #0284c7);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        .brand-title {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .brand-subtitle {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid var(--light-border);
            background: white;
            color: var(--text-main);
        }

        .header-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-bg);
        }

        .cart-header-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            position: relative;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
        }

        .cart-header-btn:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-1px);
        }

        .cart-badge {
            background: #ef4444;
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px;
            border: 2px solid white;
            position: absolute;
            top: -6px;
            right: -6px;
        }

        /* Hero Banner */
        .hero-banner {
            background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #059669 100%);
            color: white;
            padding: 36px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -20%;
            width: 140%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 60%);
            pointer-events: none;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
            backdrop-filter: blur(4px);
        }

        .hero-title {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .hero-desc {
            font-size: 14px;
            opacity: 0.9;
            max-width: 580px;
            margin: 0 auto 20px;
            line-height: 1.5;
        }

        /* Search & Filter Section */
        .filter-section {
            max-width: 1200px;
            margin: -24px auto 0;
            padding: 0 20px;
            position: relative;
            z-index: 10;
            width: 100%;
        }

        .filter-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 16px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--light-border);
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .search-row {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .search-box {
            position: relative;
            flex: 1;
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 15px;
        }

        .search-input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border-radius: var(--radius-md);
            border: 1px solid var(--light-border);
            background: #f8fafc;
            font-size: 14px;
            outline: none;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
        }

        .sort-select {
            padding: 12px 14px;
            border-radius: var(--radius-md);
            border: 1px solid var(--light-border);
            background: #f8fafc;
            font-size: 13px;
            font-weight: 500;
            outline: none;
            cursor: pointer;
            color: var(--text-main);
        }

        .sort-select:focus {
            border-color: var(--primary);
        }

        /* Category Pill Carousel */
        .categories-carousel {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 4px;
            scrollbar-width: thin;
        }

        .categories-carousel::-webkit-scrollbar {
            height: 4px;
        }

        .categories-carousel::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .category-pill {
            white-space: nowrap;
            padding: 7px 15px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 600;
            border: 1px solid var(--light-border);
            background: white;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .category-pill:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-bg);
        }

        .category-pill.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.25);
        }

        /* Main Catalog Content */
        .main-container {
            max-width: 1200px;
            margin: 24px auto 0;
            padding: 0 20px;
            width: 100%;
            flex: 1;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .product-count-badge {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            background: #e2e8f0;
            padding: 2px 8px;
            border-radius: 999px;
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
        }

        @media (max-width: 640px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
        }

        .product-card {
            background: white;
            border-radius: var(--radius-lg);
            border: 1px solid var(--light-border);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.25s ease;
            position: relative;
            box-shadow: var(--shadow-sm);
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: #cbd5e1;
        }

        .product-image-wrap {
            position: relative;
            width: 100%;
            padding-top: 85%;
            background: #f1f5f9;
            overflow: hidden;
        }

        .product-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-img {
            transform: scale(1.05);
        }

        .product-img-fallback {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            color: #94a3b8;
            font-size: 36px;
        }

        .product-category-tag {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(6px);
            color: white;
            font-size: 10.5px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 999px;
            letter-spacing: 0.3px;
        }

        .stock-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 10.5px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            gap: 4px;
            backdrop-filter: blur(6px);
        }

        .stock-available {
            background: rgba(16, 185, 129, 0.92);
            color: white;
        }

        .stock-low {
            background: rgba(245, 158, 11, 0.95);
            color: white;
        }

        .stock-empty {
            background: rgba(239, 68, 68, 0.95);
            color: white;
        }

        .product-body {
            padding: 14px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .product-name {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 6px;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 38px;
        }

        .product-meta {
            font-size: 11.5px;
            color: var(--text-muted);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .product-price {
            font-size: 17px;
            font-weight: 800;
            color: var(--primary-dark);
            margin-top: auto;
            margin-bottom: 12px;
            font-family: 'Outfit', sans-serif;
        }

        .product-price span {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Product Order Actions */
        .btn-order-initial {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 9px 12px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s ease;
            box-shadow: 0 3px 8px rgba(5, 150, 105, 0.2);
        }

        .btn-order-initial:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.35);
        }

        .btn-order-disabled {
            background: #cbd5e1 !important;
            color: #64748b !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
            transform: none !important;
        }

        /* Interactive Counter Button Group */
        .order-counter-widget {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--primary-bg);
            border: 1.5px solid var(--primary-light);
            border-radius: var(--radius-md);
            padding: 3px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(5, 150, 105, 0.15);
        }

        .counter-btn {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            border: none;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .counter-btn:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: scale(1.05);
        }

        .counter-btn:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .counter-val {
            font-size: 14px;
            font-weight: 800;
            color: var(--primary-dark);
            text-align: center;
            flex: 1;
            font-family: 'Outfit', sans-serif;
        }

        /* Sticky Bottom Checkout Bar */
        .floating-cart-bar {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(120%);
            width: calc(100% - 40px);
            max-width: 700px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(16px);
            color: white;
            padding: 14px 20px;
            border-radius: 999px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 900;
            transition: transform 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .floating-cart-bar.visible {
            transform: translateX(-50%) translateY(0);
        }

        .cart-bar-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .cart-bar-icon-wrap {
            width: 44px;
            height: 44px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            position: relative;
        }

        .cart-bar-count-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: white;
            font-size: 11px;
            font-weight: 800;
            padding: 1px 6px;
            border-radius: 999px;
            border: 2px solid #0f172a;
        }

        .cart-bar-total-label {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .cart-bar-total-price {
            font-size: 18px;
            font-weight: 800;
            color: #34d399;
            font-family: 'Outfit', sans-serif;
        }

        .cart-bar-btn {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 11px 22px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);
            transition: all 0.2s ease;
        }

        .cart-bar-btn:hover {
            transform: scale(1.03);
            background: #059669;
        }

        /* Modal Overlay & Slide-in Drawer */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            display: flex;
            justify-content: flex-end;
        }

        .modal-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        .cart-drawer {
            width: 100%;
            max-width: 480px;
            background: white;
            height: 100%;
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: -10px 0 30px rgba(0,0,0,0.15);
        }

        .modal-overlay.open .cart-drawer {
            transform: translateX(0);
        }

        .drawer-header {
            padding: 20px;
            border-bottom: 1px solid var(--light-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .drawer-title {
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .drawer-close {
            background: #f1f5f9;
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 16px;
            transition: all 0.2s;
        }

        .drawer-close:hover {
            background: #e2e8f0;
            color: var(--text-main);
        }

        .drawer-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* Cart Items List */
        .cart-items-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .cart-item-card {
            display: flex;
            gap: 12px;
            padding: 12px;
            border-radius: var(--radius-md);
            background: #f8fafc;
            border: 1px solid var(--light-border);
            align-items: center;
        }

        .cart-item-thumb {
            width: 54px;
            height: 54px;
            border-radius: var(--radius-sm);
            background: #e2e8f0;
            object-fit: cover;
            flex-shrink: 0;
        }

        .cart-item-info {
            flex: 1;
            min-width: 0;
        }

        .cart-item-title {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cart-item-price {
            font-size: 12px;
            color: var(--text-muted);
            margin: 2px 0 6px;
        }

        .cart-item-subtotal {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--primary-dark);
            font-family: 'Outfit', sans-serif;
        }

        .cart-item-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cart-mini-counter {
            display: flex;
            align-items: center;
            gap: 4px;
            background: white;
            border: 1px solid var(--light-border);
            border-radius: 6px;
            padding: 2px;
        }

        .cart-mini-btn {
            width: 24px;
            height: 24px;
            border-radius: 4px;
            border: none;
            background: #f1f5f9;
            color: var(--text-main);
            cursor: pointer;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-mini-btn:hover:not(:disabled) {
            background: var(--primary);
            color: white;
        }

        .cart-mini-qty {
            font-size: 12.5px;
            font-weight: 700;
            min-width: 22px;
            text-align: center;
        }

        .cart-item-remove {
            color: #94a3b8;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 4px;
            font-size: 14px;
            transition: color 0.2s;
        }

        .cart-item-remove:hover {
            color: var(--danger);
        }

        /* Customer Form */
        .form-section-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 5px;
        }

        .form-label .required {
            color: var(--danger);
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--light-border);
            font-size: 13.5px;
            outline: none;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 60px;
        }

        /* Drawer Footer */
        .drawer-footer {
            padding: 20px;
            border-top: 1px solid var(--light-border);
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .order-summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: var(--text-muted);
        }

        .order-summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 17px;
            font-weight: 800;
            color: var(--text-main);
            padding-top: 8px;
            border-top: 1px dashed var(--light-border);
            font-family: 'Outfit', sans-serif;
        }

        .order-summary-total span:last-child {
            color: var(--primary-dark);
        }

        .btn-submit-order {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 13px;
            border-radius: var(--radius-md);
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);
            transition: all 0.2s ease;
        }

        .btn-submit-order:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-submit-order:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* Success Dialog Modal */
        .dialog-modal {
            background: white;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 480px;
            margin: auto;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes popIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .dialog-body {
            padding: 28px 24px;
            text-align: center;
        }

        .success-icon-wrap {
            width: 70px;
            height: 70px;
            background: var(--primary-bg);
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 16px;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.25);
        }

        .order-badge-pill {
            display: inline-block;
            background: #f1f5f9;
            color: var(--text-main);
            font-weight: 800;
            font-size: 15px;
            padding: 6px 14px;
            border-radius: 8px;
            margin: 12px 0;
            border: 1px dashed #cbd5e1;
            font-family: 'Outfit', sans-serif;
            letter-spacing: 0.5px;
        }

        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 2000;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }

        .toast {
            background: white;
            color: var(--text-main);
            padding: 12px 18px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            border-left: 4px solid var(--primary);
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            pointer-events: auto;
            animation: slideLeft 0.3s ease forwards;
            max-width: 340px;
        }

        .toast.error {
            border-left-color: var(--danger);
        }

        .toast.warning {
            border-left-color: var(--accent);
        }

        @keyframes slideLeft {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Empty state & Loading */
        .state-empty {
            text-align: center;
            padding: 48px 20px;
            grid-column: 1 / -1;
            color: var(--text-muted);
        }

        .state-empty i {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 12px;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <!-- Header Navbar -->
    <header class="header">
        <div class="header-content">
            <a href="/" class="brand">
                <div class="brand-icon">
                    <i class="fa-solid fa-basket-shopping"></i>
                </div>
                <div>
                    <div class="brand-title">NURMART</div>
                    <div class="brand-subtitle"><i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 10px;"></i> Toko Buka • Pemesanan Online</div>
                </div>
            </a>

            <div class="header-actions">
                <a href="/admin" class="header-btn" title="Halaman Login Petugas / Kasir">
                    <i class="fa-solid fa-user-shield"></i>
                    <span style="display: none; @media(min-width:640px){display:inline;}">Admin / POS</span>
                </a>

                <button class="header-btn cart-header-btn" onclick="openCartDrawer()">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Keranjang</span>
                    <span id="cart-header-count" class="cart-badge" style="display: none;">0</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Banner -->
    <section class="hero-banner">
        <div class="hero-content">
            <div class="hero-tag">
                <i class="fa-solid fa-bolt"></i> Belanja Mudah & Cepat
            </div>
            <h1 class="hero-title">Katalog & Pemesanan Online NurMart</h1>
            <p class="hero-desc">Pilih barang kebutuhan harian Anda, atur jumlah pesanan secara langsung, dan kirim pesanan Anda. Stok kami selalu terupdate secara realtime!</p>
        </div>
    </section>

    <!-- Search & Filter Area -->
    <section class="filter-section">
        <div class="filter-card">
            <div class="search-row">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" class="search-input" placeholder="Cari nama produk, kategori, atau kode barang..." oninput="handleSearch(this.value)">
                </div>
                <select id="sortSelect" class="sort-select" onchange="handleSort(this.value)">
                    <option value="nama_barang">Urut: Nama (A-Z)</option>
                    <option value="harga_termurah">Urut: Termurah</option>
                    <option value="harga_termahal">Urut: Termahal</option>
                    <option value="stok_terbanyak">Urut: Stok Terbanyak</option>
                </select>
            </div>

            <!-- Category Pills -->
            <div class="categories-carousel" id="categoryPillsContainer">
                <button class="category-pill active" onclick="filterCategory(null, this)">
                    <i class="fa-solid fa-border-all"></i> Semua Kategori
                </button>
                <!-- Kategori dinamis akan dimuat via JS -->
            </div>
        </div>
    </section>

    <!-- Main Products Catalog -->
    <main class="main-container">
        <div class="section-header">
            <div class="section-title">
                <span>Daftar Produk Tersedia</span>
                <span id="productCountBadge" class="product-count-badge">0 item</span>
            </div>
            <div style="font-size: 12px; color: var(--text-muted);">
                <i class="fa-solid fa-arrows-rotate" style="cursor: pointer;" onclick="loadProducts()" title="Refresh Stok"></i> Cek Stok Otomatis
            </div>
        </div>

        <div id="productGrid" class="product-grid">
            <!-- Loading skeleton items -->
            <div class="state-empty">
                <div class="spinner" style="border-top-color: var(--primary); width: 30px; height: 30px; border-width: 3px;"></div>
                <p style="margin-top: 12px; font-weight: 600;">Memuat katalog produk...</p>
            </div>
        </div>
    </main>

    <!-- Floating Sticky Cart Bar -->
    <div id="floatingCartBar" class="floating-cart-bar">
        <div class="cart-bar-info">
            <div class="cart-bar-icon-wrap">
                <i class="fa-solid fa-bag-shopping"></i>
                <span id="floatingCartCount" class="cart-bar-count-badge">0</span>
            </div>
            <div>
                <div class="cart-bar-total-label">Total Pesanan</div>
                <div id="floatingCartPrice" class="cart-bar-total-price">Rp 0</div>
            </div>
        </div>
        <button class="cart-bar-btn" onclick="openCartDrawer()">
            <span>Lihat Keranjang</span>
            <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>

    <!-- Slide-in Cart & Checkout Drawer -->
    <div id="cartModalOverlay" class="modal-overlay" onclick="handleOverlayClick(event)">
        <div class="cart-drawer">
            <div class="drawer-header">
                <div class="drawer-title">
                    <i class="fa-solid fa-cart-shopping" style="color: var(--primary);"></i>
                    <span>Keranjang Pesanan</span>
                </div>
                <button class="drawer-close" onclick="closeCartDrawer()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body">
                <!-- Items list -->
                <div>
                    <div class="form-section-title">
                        <i class="fa-solid fa-list-check" style="color: var(--primary);"></i>
                        <span>Item yang Dipilih</span>
                    </div>
                    <div id="cartDrawerList" class="cart-items-list">
                        <!-- Dimuat dinamis -->
                    </div>
                </div>

                <!-- Customer Info Form -->
                <div style="background: #f8fafc; padding: 16px; border-radius: var(--radius-md); border: 1px solid var(--light-border);">
                    <div class="form-section-title">
                        <i class="fa-solid fa-user" style="color: var(--primary);"></i>
                        <span>Informasi Pemesan</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Pemesan <span class="required">*</span></label>
                        <input type="text" id="customerName" class="form-control" placeholder="Contoh: Ibu Rina / Bpk Budi" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">No. WhatsApp / Telepon</label>
                        <input type="tel" id="customerPhone" class="form-control" placeholder="Contoh: 081234567890 (opsional untuk konfirmasi)">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat / Catatan Pengiriman</label>
                        <textarea id="customerAddress" class="form-control" placeholder="Tuliskan alamat atau catatan tambahan jika ada..."></textarea>
                    </div>
                </div>
            </div>

            <div class="drawer-footer">
                <div class="order-summary-row">
                    <span>Total Item</span>
                    <span id="drawerTotalItems" style="font-weight: 700; color: var(--text-main);">0 pcs</span>
                </div>
                <div class="order-summary-total">
                    <span>Total Belanja:</span>
                    <span id="drawerTotalPrice">Rp 0</span>
                </div>
                <button id="btnSubmitOrder" class="btn-submit-order" onclick="submitOrder()">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Kirim Pesanan Sekarang</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Success Order Modal Dialog -->
    <div id="successModalOverlay" class="modal-overlay" style="justify-content: center; align-items: center; padding: 20px;">
        <div class="dialog-modal">
            <div class="dialog-body">
                <div class="success-icon-wrap">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h2 style="font-size: 22px; font-weight: 800; color: var(--text-main); margin-bottom: 6px;">Pesanan Berhasil Dikirim!</h2>
                <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">Stok produk telah diamankan dan pesanan Anda segera disiapkan oleh kasir Toko NurMart.</p>
                
                <div class="order-badge-pill">
                    <i class="fa-solid fa-receipt" style="color: var(--primary);"></i> No. Pesanan: <span id="successOrderNo">-</span>
                </div>

                <div style="background: #f8fafc; border-radius: var(--radius-md); padding: 14px; text-align: left; margin-bottom: 20px; font-size: 13px; border: 1px solid var(--light-border);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                        <span style="color: var(--text-muted);">Nama:</span>
                        <strong id="successCustomerName">-</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                        <span style="color: var(--text-muted);">Total Belanja:</span>
                        <strong id="successOrderTotal" style="color: var(--primary-dark); font-size: 14px;">-</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Status:</span>
                        <span style="color: #d97706; font-weight: 700;"><i class="fa-solid fa-clock"></i> Menunggu Konfirmasi</span>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a id="btnWhatsAppConfirm" href="#" target="_blank" class="btn-submit-order" style="background: #25D366; text-decoration: none;">
                        <i class="fa-brands fa-whatsapp" style="font-size: 18px;"></i>
                        <span>Konfirmasi via WhatsApp</span>
                    </a>
                    <button class="header-btn" style="width: 100%; justify-content: center; padding: 12px;" onclick="closeSuccessModal()">
                        <i class="fa-solid fa-plus"></i> Pesan Barang Lain
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div id="toastContainer" class="toast-container"></div>

    <script>
        // State
        let allProducts = [];
        let categories = [];
        let selectedCategory = null;
        let searchQuery = '';
        let sortOption = 'nama_barang';
        
        // Cart Object: { [barang_id]: { id, nama_barang, harga_jual, stok, satuan, gambar_full_url, quantity } }
        let cart = {};

        // Load Initial Data
        document.addEventListener('DOMContentLoaded', () => {
            loadCategories();
            loadProducts();
            loadSavedCart();
        });

        // Format Currency
        function formatRupiah(number) {
            return 'Rp ' + Number(number || 0).toLocaleString('id-ID');
        }

        // Show Toast Feedback
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            let icon = 'fa-circle-check';
            if (type === 'error') icon = 'fa-triangle-exclamation';
            if (type === 'warning') icon = 'fa-circle-exclamation';
            
            toast.innerHTML = `<i class="fa-solid ${icon}"></i> <span>${message}</span>`;
            container.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3200);
        }

        // Fetch Categories
        async function loadCategories() {
            try {
                const res = await fetch('/api/public/produk?all=1');
                // Alternatively fetch from category list
            } catch (err) {
                console.error(err);
            }
        }

        // Fetch Products Catalog
        async function loadProducts() {
            const grid = document.getElementById('productGrid');
            try {
                const res = await fetch(`/api/public/produk?all=1&sort_by=${sortOption}`);
                const result = await res.json();

                if (result.status && result.data) {
                    allProducts = result.data.filter(p => Number(p.stok || 0) > 0);
                    extractCategories(allProducts);
                    renderProducts();
                    renderCategoryPills();
                } else {
                    grid.innerHTML = `<div class="state-empty"><i class="fa-solid fa-box-open"></i><p>Belum ada produk yang tersedia.</p></div>`;
                }
            } catch (err) {
                console.error(err);
                grid.innerHTML = `<div class="state-empty"><i class="fa-solid fa-triangle-exclamation" style="color: #ef4444;"></i><p>Gagal memuat produk. Silakan refresh halaman.</p></div>`;
            }
        }

        function extractCategories(products) {
            const map = new Map();
            products.forEach(p => {
                if (p.kategori && !map.has(p.kategori.id)) {
                    map.set(p.kategori.id, p.kategori.nama_kategori);
                }
            });
            categories = Array.from(map.entries()).map(([id, name]) => ({ id, name }));
        }

        function renderCategoryPills() {
            const container = document.getElementById('categoryPillsContainer');
            let html = `
                <button class="category-pill ${selectedCategory === null ? 'active' : ''}" onclick="filterCategory(null, this)">
                    <i class="fa-solid fa-border-all"></i> Semua Kategori
                </button>
            `;
            categories.forEach(c => {
                const isActive = selectedCategory === c.id;
                html += `
                    <button class="category-pill ${isActive ? 'active' : ''}" onclick="filterCategory(${c.id}, this)">
                        ${c.name}
                    </button>
                `;
            });
            container.innerHTML = html;
        }

        function filterCategory(catId, btn) {
            selectedCategory = catId;
            document.querySelectorAll('.category-pill').forEach(el => el.classList.remove('active'));
            if (btn) btn.classList.add('active');
            renderProducts();
        }

        function handleSearch(val) {
            searchQuery = val.trim().toLowerCase();
            renderProducts();
        }

        function handleSort(val) {
            sortOption = val;
            loadProducts();
        }

        // Render Products Grid
        function renderProducts() {
            const grid = document.getElementById('productGrid');
            const countBadge = document.getElementById('productCountBadge');

            let filtered = allProducts.filter(p => {
                const hasStock = Number(p.stok || 0) > 0;
                const matchCat = selectedCategory ? (p.kategori_id === selectedCategory) : true;
                const matchSearch = searchQuery ? (
                    p.nama_barang.toLowerCase().includes(searchQuery) ||
                    (p.kode_sku && p.kode_sku.toLowerCase().includes(searchQuery)) ||
                    (p.kategori && p.kategori.nama_kategori.toLowerCase().includes(searchQuery))
                ) : true;
                return hasStock && matchCat && matchSearch;
            });

            countBadge.textContent = `${filtered.length} item`;

            if (filtered.length === 0) {
                grid.innerHTML = `
                    <div class="state-empty">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <p style="font-weight: 600; font-size: 15px;">Produk tidak ditemukan</p>
                        <p style="font-size: 13px;">Coba gunakan kata kunci pencarian atau kategori lain.</p>
                    </div>
                `;
                return;
            }

            grid.innerHTML = filtered.map(p => {
                const inCart = cart[p.id];
                const qty = inCart ? inCart.quantity : 0;
                const stock = Number(p.stok || 0);
                const isOutOfStock = stock <= 0;
                const isMaxReached = qty >= stock;

                // Stock Badge HTML
                let stockBadgeHtml = '';
                if (isOutOfStock) {
                    stockBadgeHtml = `<span class="stock-badge stock-empty"><i class="fa-solid fa-circle-xmark"></i> Stok Habis</span>`;
                } else if (stock <= 5) {
                    stockBadgeHtml = `<span class="stock-badge stock-low"><i class="fa-solid fa-fire"></i> Sisa ${stock} ${p.satuan || 'pcs'}</span>`;
                } else {
                    stockBadgeHtml = `<span class="stock-badge stock-available"><i class="fa-solid fa-box"></i> Stok: ${stock} ${p.satuan || 'pcs'}</span>`;
                }

                // Image HTML
                let imgHtml = '';
                if (p.gambar_full_url) {
                    imgHtml = `<img src="${p.gambar_full_url}" alt="${p.nama_barang}" class="product-img" loading="lazy" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\\'product-img-fallback\\'><i class=\\'fa-solid fa-basket-shopping\\'></i></div>';">`;
                } else {
                    imgHtml = `<div class="product-img-fallback"><i class="fa-solid fa-basket-shopping"></i></div>`;
                }

                // Action Button / Counter Widget HTML
                let actionHtml = '';
                if (isOutOfStock) {
                    actionHtml = `
                        <button class="btn-order-initial btn-order-disabled" disabled>
                            <i class="fa-solid fa-ban"></i> Stok Habis
                        </button>
                    `;
                } else if (qty > 0) {
                    // Dinamis: Tampilkan widget angka dan tombol + / -
                    actionHtml = `
                        <div class="order-counter-widget" id="counter-widget-${p.id}">
                            <button class="counter-btn" onclick="decreaseItem(${p.id})" title="Kurangi pesanan">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <div class="counter-val" id="counter-val-${p.id}">
                                ${qty} <span style="font-size: 11px; font-weight: 500; color: #047857;">${p.satuan || 'pcs'}</span>
                            </div>
                            <button class="counter-btn" onclick="increaseItem(${p.id})" ${isMaxReached ? 'disabled' : ''} title="${isMaxReached ? 'Maksimal stok tercapai' : 'Tambah pesanan'}">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    `;
                } else {
                    // Tombol Awal Pesan
                    actionHtml = `
                        <button class="btn-order-initial" onclick="initialOrderItem(${p.id})">
                            <i class="fa-solid fa-plus"></i> Pesan
                        </button>
                    `;
                }

                return `
                    <div class="product-card" id="card-product-${p.id}">
                        <div class="product-image-wrap">
                            ${imgHtml}
                            <span class="product-category-tag">${p.kategori ? p.kategori.nama_kategori : 'Umum'}</span>
                            ${stockBadgeHtml}
                        </div>
                        <div class="product-body">
                            <h3 class="product-name" title="${p.nama_barang}">${p.nama_barang}</h3>
                            <div class="product-meta">
                                <span><i class="fa-solid fa-barcode"></i> ${p.kode_sku || '-'}</span>
                                <span>Satuan: <strong>${p.satuan || 'pcs'}</strong></span>
                            </div>
                            <div class="product-price">
                                ${formatRupiah(p.harga_jual)} <span>/ ${p.satuan || 'pcs'}</span>
                            </div>
                            ${actionHtml}
                        </div>
                    </div>
                `;
            }).join('');
        }

        // ==========================================
        // Cart State & Stock Validation Logic
        // ==========================================

        // Initial add item to cart (Klik tombol Pesan)
        async function initialOrderItem(productId) {
            const product = allProducts.find(p => p.id === productId);
            if (!product) return;

            const stock = Number(product.stok || 0);
            if (stock <= 0) {
                showToast(`Maaf, stok produk '${product.nama_barang}' sedang habis.`, 'error');
                return;
            }

            // Real-time stock check via server API
            try {
                const res = await fetch('/api/public/cek-stok', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ barang_id: productId, jumlah: 1 })
                });
                const data = await res.json();
                
                if (!data.status || !data.data.is_sufficient) {
                    showToast(`Stok '${product.nama_barang}' tidak mencukupi (Tersedia: ${data.data?.stok_tersedia || 0}).`, 'error');
                    // update local product stock
                    product.stok = data.data?.stok_tersedia || 0;
                    renderProducts();
                    return;
                }
            } catch (e) {
                console.warn('Offline / local fallback stock check', e);
            }

            cart[productId] = {
                id: product.id,
                nama_barang: product.nama_barang,
                harga_jual: product.harga_jual,
                stok: stock,
                satuan: product.satuan || 'pcs',
                gambar_full_url: product.gambar_full_url,
                quantity: 1
            };

            saveCart();
            renderProducts();
            updateFloatingCartBar();
            showToast(`'${product.nama_barang}' ditambahkan ke keranjang (1 ${product.satuan || 'pcs'}).`);
        }

        // Increase quantity (+ tombol)
        async function increaseItem(productId) {
            const product = allProducts.find(p => p.id === productId);
            if (!product || !cart[productId]) return;

            const currentQty = cart[productId].quantity;
            const targetQty = currentQty + 1;
            const stock = Number(product.stok || 0);

            // Validasi lokal terlebih dahulu
            if (targetQty > stock) {
                showToast(`Maksimal pesanan tercapai! Sisa stok '${product.nama_barang}' hanya ${stock} ${product.satuan || 'pcs'}.`, 'warning');
                return;
            }

            // Cek ke server sebelum tambah
            try {
                const res = await fetch('/api/public/cek-stok', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ barang_id: productId, jumlah: targetQty })
                });
                const data = await res.json();
                
                if (!data.status || !data.data.is_sufficient) {
                    showToast(`Stok '${product.nama_barang}' tidak mencukupi untuk ${targetQty} pcs (Tersedia: ${data.data?.stok_tersedia || 0}).`, 'warning');
                    product.stok = data.data?.stok_tersedia || 0;
                    cart[productId].stok = product.stok;
                    renderProducts();
                    return;
                }
            } catch (e) {
                console.warn('Stock verify network error', e);
            }

            cart[productId].quantity = targetQty;
            saveCart();
            renderProducts();
            updateFloatingCartBar();
            renderCartDrawer();
        }

        // Decrease quantity (- tombol)
        function decreaseItem(productId) {
            if (!cart[productId]) return;

            const currentQty = cart[productId].quantity;
            if (currentQty <= 1) {
                // Hapus dari keranjang
                const nama = cart[productId].nama_barang;
                delete cart[productId];
                showToast(`'${nama}' dihapus dari keranjang.`, 'warning');
            } else {
                cart[productId].quantity = currentQty - 1;
            }

            saveCart();
            renderProducts();
            updateFloatingCartBar();
            renderCartDrawer();
        }

        // Remove item completely
        function removeItemFromCart(productId) {
            if (cart[productId]) {
                const nama = cart[productId].nama_barang;
                delete cart[productId];
                saveCart();
                renderProducts();
                updateFloatingCartBar();
                renderCartDrawer();
                showToast(`'${nama}' dihapus dari keranjang.`, 'warning');
            }
        }

        // Save & Load Cart LocalStorage
        function saveCart() {
            localStorage.setItem('nurmart_public_cart', JSON.stringify(cart));
        }

        function loadSavedCart() {
            try {
                const saved = localStorage.getItem('nurmart_public_cart');
                if (saved) {
                    cart = JSON.parse(saved);
                    updateFloatingCartBar();
                }
            } catch (e) {
                cart = {};
            }
        }

        // Update Floating Cart Bar
        function updateFloatingCartBar() {
            const bar = document.getElementById('floatingCartBar');
            const headerCount = document.getElementById('cart-header-count');
            const floatingCount = document.getElementById('floatingCartCount');
            const floatingPrice = document.getElementById('floatingCartPrice');

            const items = Object.values(cart);
            let totalQty = 0;
            let totalPrice = 0;

            items.forEach(item => {
                totalQty += item.quantity;
                totalPrice += item.quantity * item.harga_jual;
            });

            if (totalQty > 0) {
                bar.classList.add('visible');
                headerCount.style.display = 'inline-block';
                headerCount.textContent = totalQty;
                floatingCount.textContent = totalQty;
                floatingPrice.textContent = formatRupiah(totalPrice);
            } else {
                bar.classList.remove('visible');
                headerCount.style.display = 'none';
            }
        }

        // ==========================================
        // Drawer & Checkout Flow
        // ==========================================

        function openCartDrawer() {
            renderCartDrawer();
            document.getElementById('cartModalOverlay').classList.add('open');
        }

        function closeCartDrawer() {
            document.getElementById('cartModalOverlay').classList.remove('open');
        }

        function handleOverlayClick(e) {
            if (e.target.id === 'cartModalOverlay') {
                closeCartDrawer();
            }
        }

        function renderCartDrawer() {
            const listContainer = document.getElementById('cartDrawerList');
            const totalItemsEl = document.getElementById('drawerTotalItems');
            const totalPriceEl = document.getElementById('drawerTotalPrice');
            const btnSubmit = document.getElementById('btnSubmitOrder');

            const items = Object.values(cart);
            let totalQty = 0;
            let totalPrice = 0;

            if (items.length === 0) {
                listContainer.innerHTML = `
                    <div style="text-align: center; padding: 32px 10px; color: var(--text-muted);">
                        <i class="fa-solid fa-cart-arrow-down" style="font-size: 40px; color: #cbd5e1; margin-bottom: 10px;"></i>
                        <p style="font-weight: 600; font-size: 14px;">Keranjang Masih Kosong</p>
                        <p style="font-size: 12px;">Silakan pilih produk dari katalog untuk mulai memesan.</p>
                    </div>
                `;
                totalItemsEl.textContent = '0 pcs';
                totalPriceEl.textContent = 'Rp 0';
                btnSubmit.disabled = true;
                return;
            }

            btnSubmit.disabled = false;

            listContainer.innerHTML = items.map(item => {
                totalQty += item.quantity;
                const subtotal = item.quantity * item.harga_jual;
                totalPrice += subtotal;

                const isMax = item.quantity >= item.stok;

                return `
                    <div class="cart-item-card">
                        ${item.gambar_full_url ? 
                            `<img src="${item.gambar_full_url}" class="cart-item-thumb" alt="${item.nama_barang}">` : 
                            `<div class="cart-item-thumb" style="display:flex;align-items:center;justify-content:center;color:#94a3b8;"><i class="fa-solid fa-box"></i></div>`
                        }
                        <div class="cart-item-info">
                            <div class="cart-item-title" title="${item.nama_barang}">${item.nama_barang}</div>
                            <div class="cart-item-price">${formatRupiah(item.harga_jual)} / ${item.satuan}</div>
                            <div class="cart-item-subtotal">${formatRupiah(subtotal)}</div>
                        </div>
                        <div class="cart-item-controls">
                            <div class="cart-mini-counter">
                                <button class="cart-mini-btn" onclick="decreaseItem(${item.id})">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <span class="cart-mini-qty">${item.quantity}</span>
                                <button class="cart-mini-btn" onclick="increaseItem(${item.id})" ${isMax ? 'disabled' : ''}>
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                            <button class="cart-item-remove" onclick="removeItemFromCart(${item.id})" title="Hapus">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');

            totalItemsEl.textContent = `${totalQty} item`;
            totalPriceEl.textContent = formatRupiah(totalPrice);
        }

        // Submit Order to Server
        async function submitOrder() {
            const nameInput = document.getElementById('customerName');
            const phoneInput = document.getElementById('customerPhone');
            const addressInput = document.getElementById('customerAddress');
            const btnSubmit = document.getElementById('btnSubmitOrder');

            const namaPemesan = nameInput.value.trim();
            if (!namaPemesan) {
                showToast('Mohon masukkan Nama Pemesan.', 'error');
                nameInput.focus();
                return;
            }

            const items = Object.values(cart).map(item => ({
                barang_id: item.id,
                jumlah: item.quantity
            }));

            if (items.length === 0) {
                showToast('Keranjang pesanan masih kosong.', 'error');
                return;
            }

            // UI Loading state
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = `<span class="spinner"></span> <span>Memproses Pesanan & Cek Stok...</span>`;

            try {
                const payload = {
                    nama_pemesan: namaPemesan,
                    no_telepon: phoneInput.value.trim() || null,
                    alamat: addressInput.value.trim() || null,
                    items: items
                };

                const res = await fetch('/api/public/pesanan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();

                if (!res.ok || !data.status) {
                    throw new Error(data.message || 'Terjadi kesalahan saat memproses pesanan.');
                }

                // Sukses!
                const order = data.data.pesanan;
                const kontakToko = data.data.kontak_toko || {};

                // Trigger confetti celebration!
                if (typeof confetti === 'function') {
                    confetti({
                        particleCount: 100,
                        spread: 70,
                        origin: { y: 0.6 }
                    });
                }

                // Tampilkan Modal Sukses
                document.getElementById('successOrderNo').textContent = order.no_pesanan;
                document.getElementById('successCustomerName').textContent = order.nama_pemesan;
                document.getElementById('successOrderTotal').textContent = formatRupiah(order.total_harga);

                // Siapkan link WhatsApp konfirmasi
                let waPhone = kontakToko.telepon || '6281234567890';
                waPhone = waPhone.replace(/^0/, '62').replace(/[^0-9]/g, '');

                let waText = `Halo ${kontakToko.nama_toko || 'NurMart'}, saya telah membuat pesanan online:\n\n` +
                             `*No. Pesanan:* ${order.no_pesanan}\n` +
                             `*Nama Pemesan:* ${order.nama_pemesan}\n` +
                             (order.no_telepon ? `*No. HP:* ${order.no_telepon}\n` : '') +
                             (order.alamat ? `*Alamat:* ${order.alamat}\n` : '') +
                             `*Total Belanja:* ${formatRupiah(order.total_harga)}\n\n` +
                             `*Rincian Barang:*\n`;

                if (order.details) {
                    order.details.forEach((d, idx) => {
                        const bName = d.barang ? d.barang.nama_barang : 'Barang';
                        waText += `${idx + 1}. ${bName} (${d.jumlah}x) - ${formatRupiah(d.subtotal)}\n`;
                    });
                }
                waText += `\nMohon bantuannya untuk diproses. Terima kasih!`;

                const waUrl = `https://api.whatsapp.com/send?phone=${waPhone}&text=${encodeURIComponent(waText)}`;
                document.getElementById('btnWhatsAppConfirm').href = waUrl;

                // Reset keranjang & form
                cart = {};
                saveCart();
                nameInput.value = '';
                phoneInput.value = '';
                addressInput.value = '';

                closeCartDrawer();
                document.getElementById('successModalOverlay').classList.add('open');

                // Refresh katalog produk untuk menampilkan sisa stok terbaru
                loadProducts();
                updateFloatingCartBar();

            } catch (err) {
                console.error(err);
                showToast(err.message, 'error');
                // Refresh stok produk karena mungkin ada yang habis
                loadProducts();
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = `<i class="fa-solid fa-paper-plane"></i> <span>Kirim Pesanan Sekarang</span>`;
            }
        }

        function closeSuccessModal() {
            document.getElementById('successModalOverlay').classList.remove('open');
        }
    </script>
</body>
</html>
