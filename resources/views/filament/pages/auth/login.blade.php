<x-filament-panels::page.simple>

    <style>
        /* =========================================================
           REMOVE FILAMENT / LARAVEL DEFAULT HEADER
        ========================================================= */

        .fi-simple-header,
        .fi-simple-header-logo,
        .fi-simple-header-brand,
        .fi-simple-layout>header,
        .fi-simple-layout header,
        .fi-logo {
            display: none !important;
        }

        .fi-simple-layout,
        .fi-simple-main,
        .fi-simple-page {
            width: 100% !important;
            max-width: none !important;
            min-height: 100vh !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .fi-simple-main {
            overflow: hidden !important;
        }


        /* =========================================================
           MAIN WEBSITE
        ========================================================= */

        .agos-login-page {
            position: relative;
            min-height: 100vh;
            width: 100%;
            overflow: hidden;

            background:
                radial-gradient(circle at 12% 20%,
                    rgba(255, 255, 255, .95) 0%,
                    rgba(255, 255, 255, .55) 20%,
                    transparent 43%),
                linear-gradient(135deg,
                    #f5fbfe 0%,
                    #e6f5fb 48%,
                    #d4edf7 100%);

            color: #17365d;
        }


        /* =========================================================
           WATER BACKGROUND EFFECT
        ========================================================= */

        .agos-water {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .agos-water::before {
            content: "";

            position: absolute;

            width: 780px;
            height: 780px;

            left: -360px;
            bottom: -490px;

            border-radius: 50%;

            border: 2px solid rgba(39, 151, 211, .16);

            box-shadow:
                0 0 0 35px rgba(39, 151, 211, .055),
                0 0 0 75px rgba(39, 151, 211, .04),
                0 0 0 120px rgba(39, 151, 211, .03),
                0 0 0 175px rgba(39, 151, 211, .02);
        }

        .agos-water::after {
            content: "";

            position: absolute;

            width: 1000px;
            height: 650px;

            right: -500px;
            bottom: -280px;

            border-radius: 50%;

            border-top:
                1px solid rgba(255, 255, 255, .8);

            box-shadow:
                0 -55px 0 -54px rgba(255, 255, 255, .75),
                0 -110px 0 -109px rgba(255, 255, 255, .6),
                0 -165px 0 -164px rgba(255, 255, 255, .45),
                0 -220px 0 -219px rgba(255, 255, 255, .3);

            transform: rotate(-12deg);
        }


        /* =========================================================
           TOP BAR
        ========================================================= */

        .agos-topbar {
            position: relative;
            z-index: 10;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding:
                22px 5vw;
        }

        .agos-top-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }


        /* =========================================================
           TOP AGOS LOGO
        ========================================================= */

        .agos-top-icon {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 42px;
            height: 42px;

            border-radius: 11px;

            background:
                rgba(255, 255, 255, .75);

            border:
                1px solid rgba(42, 135, 181, .13);

            box-shadow:
                0 7px 20px rgba(45, 130, 170, .08);

            overflow: hidden;
        }

        .agos-top-icon img {
            width: 34px;
            height: 34px;

            object-fit: contain;

            filter:
                drop-shadow(0 4px 8px rgba(31, 128, 187, .12));
        }

        .agos-top-name {
            font-size: 14px;

            font-weight: 900;

            letter-spacing: .15em;

            color: #193b61;
        }


        /* =========================================================
           THEME BUTTON
        ========================================================= */

        .agos-theme {
            display: flex;

            align-items: center;
            justify-content: center;

            width: 38px;
            height: 38px;

            border-radius: 11px;

            border:
                1px solid rgba(42, 135, 181, .13);

            background:
                rgba(255, 255, 255, .75);

            color: #315d78;

            cursor: pointer;

            box-shadow:
                0 7px 20px rgba(45, 130, 170, .08);

            transition: .2s ease;
        }

        .agos-theme:hover {
            transform: translateY(-2px);
        }

        .agos-theme svg {
            width: 19px;
            height: 19px;
        }


        /* =========================================================
           MAIN CONTENT
        ========================================================= */

        .agos-container {
            position: relative;
            z-index: 5;

            display: grid;

            grid-template-columns:
                minmax(360px, 1fr) minmax(420px, 485px);

            align-items: center;

            width: min(1120px, 90%);

            min-height:
                calc(100vh - 90px);

            margin: 0 auto;

            padding-bottom: 45px;

            gap:
                clamp(55px,
                    8vw,
                    110px);
        }


        /* =========================================================
           LEFT BRANDING
        ========================================================= */

        .agos-branding {
            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: center;

            text-align: center;
        }


        /* =========================================================
           MAIN LOGO
           SAME SIZE + MOVED UP + NO EXTRA GAP
        ========================================================= */

        .agos-logo {
            display: flex;

            align-items: center;
            justify-content: center;

            width: 185px;
            height: 185px;

            margin-top: 0;
            margin-bottom: -45px;

            transform:
                translateY(-35px);
        }

        .agos-logo img {
            width: 185px;
            height: 185px;

            object-fit: contain;

            filter:
                drop-shadow(0 10px 18px rgba(31, 128, 187, .15));
        }


        /* =========================================================
           AGOS TITLE
        ========================================================= */

        .agos-name {
            margin: 0;

            font-size:
                clamp(65px,
                    7vw,
                    92px);

            line-height: .9;

            font-weight: 950;

            letter-spacing: .08em;

            color: #122f55;
        }


        /* =========================================================
           SUBTITLE
        ========================================================= */

        .agos-system {
            margin-top: 15px;

            font-size:
                clamp(13px,
                    1.4vw,
                    16px);

            font-weight: 800;

            letter-spacing: .18em;

            text-transform: uppercase;

            color: #168ddd;
        }


        /* =========================================================
           ONLINE
        ========================================================= */

        .agos-online {
            display: flex;

            align-items: center;

            gap: 9px;

            margin-top: 19px;

            font-size: 11px;

            font-weight: 900;

            letter-spacing: .15em;

            color: #13a452;
        }

        .agos-online-dot {
            width: 9px;
            height: 9px;

            border-radius: 50%;

            background: #19b75f;

            box-shadow:
                0 0 0 5px rgba(25, 183, 95, .1),

                0 0 13px rgba(25, 183, 95, .35);
        }


        /* =========================================================
           DIVIDER
        ========================================================= */

        .agos-line {
            width: 250px;
            max-width: 80%;

            height: 1px;

            margin-top: 25px;

            background:
                linear-gradient(90deg,
                    transparent,
                    rgba(46, 147, 194, .3),
                    transparent);
        }


        /* =========================================================
           DESCRIPTION
        ========================================================= */

        .agos-description {
            margin-top: 18px;

            max-width: 300px;

            font-size: 12px;

            line-height: 1.7;

            color: #617a91;
        }


        /* =========================================================
           LOGIN CARD
           BIGGER
        ========================================================= */

        .agos-login-card {
            position: relative;

            width: 100%;

            max-width: 485px;

            padding:
                36px 38px;

            border-radius: 18px;

            background:
                rgba(255, 255, 255, .91);

            border:
                1px solid rgba(41, 124, 164, .1);

            box-shadow:
                0 25px 65px rgba(37, 99, 127, .13),

                0 5px 20px rgba(37, 99, 127, .06);

            backdrop-filter: blur(16px);

            -webkit-backdrop-filter:
                blur(16px);
        }


        /* =========================================================
           TOP BLUE LINE
        ========================================================= */

        .agos-login-card::before {
            content: "";

            position: absolute;

            top: 0;
            left: 15%;
            right: 15%;

            height: 3px;

            border-radius:
                0 0 10px 10px;

            background:
                linear-gradient(90deg,
                    #258fd7,
                    #3bb7e8);
        }


        /* =========================================================
           CARD HEADER
        ========================================================= */

        .agos-card-header {
            text-align: center;

            margin-bottom: 18px;
        }

        .agos-card-header h2 {
            margin: 0;

            font-size: 22px;

            line-height: 1.2;

            font-weight: 900;

            letter-spacing: .035em;

            text-transform: uppercase;

            color: #17375e;
        }

        .agos-card-header p {
            margin-top: 6px;

            font-size: 11px;

            color: #71869a;
        }


        /* =========================================================
           FILAMENT FORM
           REMOVE EXCESSIVE SPACING
        ========================================================= */

        .agos-login-card form {
            gap: 10px !important;
        }

        .agos-login-card .fi-fo-component-ctn {
            gap: 10px !important;
        }

        .agos-login-card .fi-fo-field-wrp {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }


        /* =========================================================
           FORM LABEL
        ========================================================= */

        .agos-login-card .fi-fo-field-wrp-label {
            margin-bottom: 5px !important;
        }

        .agos-login-card label {
            font-size: 11px !important;
        }


        /* =========================================================
           INPUT BOX
        ========================================================= */

        .agos-login-card .fi-input-wrp {
            min-height: 40px !important;

            height: 40px !important;

            background:
                #fbfdff !important;

            border:
                1px solid #d6e3ec !important;

            border-radius:
                8px !important;

            box-shadow:
                none !important;
        }

        .agos-login-card .fi-input-wrp:focus-within {
            border-color:
                #35a9df !important;

            box-shadow:
                0 0 0 1px rgba(53, 169, 223, .15) !important;
        }


        /* =========================================================
           INPUT TEXT
        ========================================================= */

        .agos-login-card .fi-input {
            min-height: 38px !important;

            height: 38px !important;

            padding-top: 6px !important;

            padding-bottom: 6px !important;

            font-size: 13px !important;

            line-height: 1.2 !important;

            color: #274968 !important;
        }

        .agos-login-card input {
            color: #274968 !important;

            font-size: 13px !important;
        }


        /* =========================================================
           CHECKBOX / REMEMBER ME
        ========================================================= */

        .agos-login-card .fi-checkbox-list {
            margin-top: 0 !important;

            margin-bottom: 0 !important;

            gap: 6px !important;
        }

        .agos-login-card .fi-checkbox {
            min-height: 20px !important;
        }

        .agos-login-card .fi-checkbox-label {
            font-size: 12px !important;
        }


        /* =========================================================
           FORM ACTION AREA
        ========================================================= */

        .agos-login-card .mt-6 {
            margin-top: 12px !important;
        }

        .agos-login-card .mt-5 {
            margin-top: 12px !important;
        }

        .agos-login-card .fi-form-actions {
            margin-top: 0 !important;
        }


        /* =========================================================
           SIGN IN BUTTON
        ========================================================= */

        .agos-login-card .fi-btn {
            width: 100% !important;

            min-height: 42px !important;

            height: 42px !important;

            padding:
                8px 14px !important;

            border-radius:
                8px !important;

            background:
                linear-gradient(90deg,
                    #2c94db,
                    #36afe1) !important;

            border:
                none !important;

            box-shadow:
                0 9px 20px rgba(42, 149, 211, .2);

            font-size:
                11px !important;

            font-weight:
                900 !important;

            letter-spacing: .08em;

            text-transform: uppercase;

            transition: .2s ease;
        }

        .agos-login-card .fi-btn:hover {
            transform:
                translateY(-1px);

            box-shadow:
                0 13px 25px rgba(42, 149, 211, .27);
        }


        /* =========================================================
           OR DIVIDER
        ========================================================= */

        .agos-or {
            display: flex;

            align-items: center;

            gap: 15px;

            margin:
                16px 0 14px;

            color: #8ba0b2;

            font-size: 9px;

            font-weight: 700;
        }

        .agos-or::before,
        .agos-or::after {
            content: "";

            flex: 1;

            height: 1px;

            background:
                #e1eaf0;
        }


        /* =========================================================
           STATUS BOXES
        ========================================================= */

        .agos-status {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 8px;
        }

        .agos-status-item {
            display: flex;

            align-items: center;

            gap: 7px;

            padding:
                8px 7px;

            border-radius: 9px;

            border:
                1px solid #e5edf2;

            background:
                rgba(250, 253, 255, .85);

            box-shadow:
                0 5px 14px rgba(30, 100, 130, .04);
        }

        .agos-status-icon {
            display: flex;

            align-items: center;

            justify-content: center;

            flex-shrink: 0;

            width: 30px;
            height: 30px;

            border-radius: 50%;

            background:
                #e6f5fc;

            color: #268fd3;
        }

        .agos-status-icon svg {
            width: 16px;
            height: 16px;
        }

        .agos-status-label {
            font-size: 7px;

            text-transform: uppercase;

            letter-spacing: .05em;

            color: #7890a2;
        }

        .agos-status-value {
            margin-top: 2px;

            font-size: 9px;

            font-weight: 900;

            letter-spacing: .04em;

            color: #208fd4;
        }

        .agos-status-item:first-child .agos-status-value {
            color: #16a55a;
        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .agos-footer {
            position: absolute;

            z-index: 10;

            bottom: 15px;

            left: 0;
            right: 0;

            text-align: center;

            font-size: 9px;

            color: #71879a;
        }


        /* =========================================================
           DARK MODE
        ========================================================= */

        html.dark .agos-login-page {
            background:
                radial-gradient(circle at 15% 15%,
                    rgba(23, 83, 112, .3),
                    transparent 35%),
                linear-gradient(135deg,
                    #071b2a,
                    #0b2b3d 55%,
                    #071923);
        }

        html.dark .agos-top-icon,
        html.dark .agos-theme {
            background:
                rgba(8, 36, 51, .85);

            border-color:
                rgba(83, 194, 230, .15);

            color: #62d0f0;
        }

        html.dark .agos-top-name {
            color: #e9f8ff;
        }

        html.dark .agos-name {
            color: #f0fbff;
        }

        html.dark .agos-system {
            color: #52c8ed;
        }

        html.dark .agos-description {
            color: #8da7b7;
        }

        html.dark .agos-login-card {
            background:
                rgba(8, 28, 40, .93);

            border-color:
                rgba(92, 199, 229, .13);

            box-shadow:
                0 30px 70px rgba(0, 0, 0, .35);
        }

        html.dark .agos-card-header h2 {
            color: #f0fbff;
        }

        html.dark .agos-card-header p {
            color: #8da8b8;
        }

        html.dark .agos-login-card .fi-input-wrp {
            background:
                #071d2a !important;

            border-color:
                #244b5d !important;
        }

        html.dark .agos-login-card input {
            color: #e7f7fc !important;
        }

        html.dark .agos-or::before,
        html.dark .agos-or::after {
            background:
                #254452;
        }

        html.dark .agos-status-item {
            background:
                rgba(12, 52, 69, .45);

            border-color:
                rgba(83, 181, 212, .12);
        }

        html.dark .agos-status-icon {
            background:
                rgba(35, 139, 180, .15);
        }

        html.dark .agos-footer {
            color:
                #7692a2;
        }


        /* =========================================================
           TABLET
        ========================================================= */

        @media (max-width: 950px) {

            .agos-container {
                grid-template-columns: 1fr;

                width:
                    min(600px, 90%);

                gap: 30px;

                min-height: auto;

                padding:
                    20px 0 55px;
            }

            .agos-branding {
                padding-top: 15px;
            }

            .agos-login-card {
                max-width: 485px;

                margin:
                    0 auto;
            }

            .agos-footer {
                position: relative;

                bottom: auto;

                margin-top: -30px;

                padding-bottom: 15px;
            }
        }


        /* =========================================================
           MOBILE
        ========================================================= */

        @media (max-width: 600px) {

            .agos-topbar {
                padding:
                    14px 18px;
            }

            .agos-top-icon {
                width: 38px;
                height: 38px;
            }

            .agos-top-icon img {
                width: 31px;
                height: 31px;
            }

            .agos-theme {
                width: 35px;
                height: 35px;
            }

            .agos-top-name {
                font-size: 11px;
            }


            .agos-container {
                width:
                    calc(100% - 24px);

                gap: 22px;

                padding-top: 8px;
            }


            /* =====================================================
               MOBILE LOGO
            ===================================================== */

            .agos-logo {
                width: 105px;
                height: 105px;

                margin-top: 0;

                margin-bottom: -20px;

                transform:
                    translateY(-15px);
            }

            .agos-logo img {
                width: 105px;
                height: 105px;
            }


            .agos-name {
                font-size: 57px;
            }

            .agos-system {
                font-size: 9px;

                letter-spacing: .14em;

                margin-top: 11px;
            }

            .agos-online {
                margin-top: 15px;

                font-size: 9px;
            }

            .agos-description {
                font-size: 10px;

                max-width: 270px;
            }


            /* =====================================================
               MOBILE LOGIN CARD
            ===================================================== */

            .agos-login-card {
                max-width: 455px;

                padding:
                    27px 18px;

                border-radius: 17px;
            }

            .agos-card-header {
                margin-bottom: 18px;
            }

            .agos-card-header h2 {
                font-size: 20px;
            }

            .agos-card-header p {
                font-size: 10px;
            }


            /* =====================================================
               MOBILE INPUT
            ===================================================== */

            .agos-login-card .fi-input-wrp {
                min-height:
                    40px !important;

                height:
                    40px !important;
            }

            .agos-login-card .fi-input {
                min-height:
                    38px !important;

                height:
                    38px !important;
            }


            /* =====================================================
               MOBILE BUTTON
            ===================================================== */

            .agos-login-card .fi-btn {
                min-height:
                    42px !important;

                height:
                    42px !important;
            }


            /* =====================================================
               MOBILE STATUS
            ===================================================== */

            .agos-status {
                gap: 6px;
            }

            .agos-status-item {
                flex-direction: column;

                justify-content: center;

                text-align: center;

                gap: 5px;

                padding:
                    9px 4px;
            }

            .agos-status-icon {
                width: 31px;
                height: 31px;
            }

            .agos-footer {
                font-size: 8px;
            }
        }


        /* =========================================================
           VERY SMALL PHONE
        ========================================================= */

        @media (max-width: 380px) {

            .agos-logo {
                width: 90px;
                height: 90px;

                margin-top: 0;

                margin-bottom: -17px;

                transform:
                    translateY(-12px);
            }

            .agos-logo img {
                width: 90px;
                height: 90px;
            }

            .agos-name {
                font-size: 50px;
            }

            .agos-login-card {
                padding:
                    25px 15px;
            }

            .agos-card-header h2 {
                font-size: 18px;
            }

            .agos-status-label {
                font-size: 7px;
            }

            .agos-status-value {
                font-size: 8px;
            }
        }


        /* =========================================================
           LANDSCAPE PHONE
        ========================================================= */

        @media (max-height: 560px) and (orientation: landscape) and (max-width: 900px) {

            .agos-topbar {
                padding:
                    8px 18px;
            }

            .agos-container {
                grid-template-columns:
                    .9fr 1.1fr;

                width: 94%;

                gap: 35px;

                padding:
                    10px 0 35px;
            }

            .agos-branding {
                align-items: center;
            }


            /* =====================================================
               LANDSCAPE LOGO
            ===================================================== */

            .agos-logo {
                width: 82px;
                height: 82px;

                margin-top: 0;

                margin-bottom: -15px;

                transform:
                    translateY(-10px);
            }

            .agos-logo img {
                width: 82px;
                height: 82px;
            }


            .agos-name {
                font-size: 48px;
            }

            .agos-system {
                font-size: 7px;
            }

            .agos-online {
                margin-top: 8px;

                font-size: 7px;
            }

            .agos-description,
            .agos-line {
                display: none;
            }

            .agos-login-card {
                padding:
                    20px 25px;
            }

            .agos-card-header {
                margin-bottom: 15px;
            }

            .agos-card-header h2 {
                font-size: 18px;
            }

            .agos-or {
                margin:
                    15px 0;
            }

            .agos-footer {
                bottom: 5px;
            }
        }
    </style>


    {{-- =========================================================
         AGOS PAGE
    ========================================================= --}}

    <div class="agos-login-page">


        {{-- =====================================================
             WATER BACKGROUND
        ====================================================== --}}

        <div class="agos-water"></div>


        {{-- =====================================================
             TOP BAR
        ====================================================== --}}

        <div class="agos-topbar">

            <div class="agos-top-brand">

                <div class="agos-top-icon">

                    <div class="agos-top-icon">
                        <img src="{{ asset('images/agos-logo.png') }}" alt="AGOS Logo">
                    </div>

                </div>

                <span class="agos-top-name">
                    AGOS
                </span>

            </div>


            {{-- =================================================
                 DARK / LIGHT MODE
            ================================================== --}}

            <button
                type="button"
                class="agos-theme"
                id="agos-theme-toggle"
                aria-label="Toggle dark mode">

                <svg
                    id="agos-sun"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.7">

                    <circle
                        cx="12"
                        cy="12"
                        r="4" />

                    <path
                        stroke-linecap="round"
                        d="
                            M12 2v2
                            M12 20v2
                            M4.93 4.93l1.42 1.42
                            M17.65 17.65l1.42 1.42
                            M2 12h2
                            M20 12h2
                            M4.93 19.07l1.42-1.42
                            M17.65 6.35l1.42-1.42
                        " />

                </svg>


                <svg
                    id="agos-moon"
                    style="display:none;"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.7">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="
                            M21 12.8
                            A8.5 8.5 0 1
                            1 11.2 3
                            a6.6 6.6 0 0
                            0 9.8 9.8z
                        " />

                </svg>

            </button>

        </div>


        {{-- =====================================================
             MAIN
        ====================================================== --}}

        <div class="agos-container">


            {{-- =================================================
                 LEFT SIDE
            ================================================== --}}

            <div class="agos-branding">


                {{-- MAIN LOGO --}}

                <div class="agos-logo">
                    <img src="{{ asset('images/Logo-no-background.png') }}" alt="AGOS Logo">
                </div>


                {{-- AGOS --}}

                <h1 class="agos-name">
                    AGOS
                </h1>


                {{-- SYSTEM NAME --}}

                <div class="agos-system">
                    Flood Monitoring System
                </div>


                {{-- ONLINE --}}

                <div class="agos-online">

                    <span class="agos-online-dot"></span>

                    SYSTEM ONLINE

                </div>


                {{-- LINE --}}

                <div class="agos-line"></div>


                {{-- DESCRIPTION --}}

                <div class="agos-description">

                    Real-time monitoring and early warning system
                    for flood detection and prevention.

                </div>

            </div>


            {{-- =================================================
                 RIGHT SIDE LOGIN CARD
            ================================================== --}}

            <div class="agos-login-card">


                {{-- CARD HEADER --}}

                <div class="agos-card-header">

                    <h2>
                        Administrator Login
                    </h2>

                    <p>
                        Authorized access only
                    </p>

                </div>


                {{-- =================================================
                     ACTUAL FILAMENT LOGIN FORM
                ================================================== --}}

                <x-filament-panels::form
                    wire:submit="authenticate">

                    {{ $this->form }}


                    <div class="mt-3">

                        <x-filament-panels::form.actions
                            :actions="$this->getCachedFormActions()"
                            :full-width="$this->hasFullWidthFormActions()" />

                    </div>

                </x-filament-panels::form>


                {{-- =================================================
                     OR
                ================================================== --}}

                <div class="agos-or">
                    OR
                </div>


                {{-- =================================================
                     SYSTEM STATUS
                ================================================== --}}

                <div class="agos-status">


                    {{-- SYSTEM --}}

                    <div class="agos-status-item">

                        <div class="agos-status-icon">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.7">

                                <rect
                                    x="3"
                                    y="4"
                                    width="18"
                                    height="13"
                                    rx="1" />

                                <path
                                    stroke-linecap="round"
                                    d="
                                        M8 21h8
                                        M12 17v4
                                    " />

                            </svg>

                        </div>

                        <div>

                            <div class="agos-status-label">
                                System
                            </div>

                            <div class="agos-status-value">
                                ONLINE
                            </div>

                        </div>

                    </div>


                    {{-- MONITORING --}}

                    <div class="agos-status-item">

                        <div class="agos-status-icon">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.7">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="
                                        M3 12h4l2-7
                                        4 14
                                        2-7h6
                                    " />

                            </svg>

                        </div>

                        <div>

                            <div class="agos-status-label">
                                Monitoring
                            </div>

                            <div class="agos-status-value">
                                ACTIVE
                            </div>

                        </div>

                    </div>


                    {{-- SECURITY --}}

                    <div class="agos-status-item">

                        <div class="agos-status-icon">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.7">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="
                                        M12 3
                                        L19 6
                                        V11
                                        C19 16 16 19 12 21
                                        C8 19 5 16 5 11
                                        V6
                                        Z
                                    " />

                                <path
                                    stroke-linecap="round"
                                    d="
                                        M9 12
                                        l2 2
                                        4-4
                                    " />

                            </svg>

                        </div>

                        <div>

                            <div class="agos-status-label">
                                Security
                            </div>

                            <div class="agos-status-value">
                                SECURE
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <div class="agos-footer">

            © {{ date('Y') }} AGOS Flood Monitoring System.
            All rights reserved.

        </div>

    </div>


    {{-- =========================================================
         LIGHT / DARK MODE SCRIPT
    ========================================================= --}}

    <script>
        (() => {

            const root =
                document.documentElement;

            const button =
                document.getElementById(
                    'agos-theme-toggle'
                );

            const sun =
                document.getElementById(
                    'agos-sun'
                );

            const moon =
                document.getElementById(
                    'agos-moon'
                );


            function updateIcon() {

                const dark =
                    root.classList.contains('dark');


                if (sun) {

                    sun.style.display =
                        dark ?
                        'none' :
                        'block';

                }


                if (moon) {

                    moon.style.display =
                        dark ?
                        'block' :
                        'none';

                }

            }


            const saved =
                localStorage.getItem(
                    'agos-theme'
                );


            if (saved === 'dark') {

                root.classList.add('dark');

            }


            if (saved === 'light') {

                root.classList.remove('dark');

            }


            updateIcon();


            button?.addEventListener(
                'click',
                () => {

                    const dark =
                        root.classList.toggle(
                            'dark'
                        );


                    localStorage.setItem(
                        'agos-theme',
                        dark ?
                        'dark' :
                        'light'
                    );


                    updateIcon();

                }
            );

        })();
    </script>

</x-filament-panels::page.simple>