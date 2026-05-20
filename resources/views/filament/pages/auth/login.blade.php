@push('styles')
    <style>
        html,
        body.fi-body.fi-panel-admin {
            min-height: 100%;
            background: #FFF6D7;
        }

        body.fi-body.fi-panel-admin {
            overflow: hidden;
        }

        .fi-simple-layout {
            min-height: 100vh;
            background-image: url("{{ asset('assets/images/bg/bg-daftar.png') }}");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .fi-simple-main-ctn {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px 16px;
        }

        .fi-simple-main {
            width: min(384px, calc(100vw - 32px)) !important;
            max-width: calc(100vw - 32px) !important;
        }

        .spies-admin-login-card {
            width: 100%;
            height: 464px;
            padding: 46px 30px 60px;
            border: 1px solid rgba(120, 113, 108, .75);
            border-radius: 24px;
            background: rgba(255, 246, 215, .9);
            box-shadow: 0 4px 4px rgba(0, 0, 0, .25);
            overflow: hidden;
        }

        .spies-admin-logo {
            display: block;
            width: 253px;
            height: 79px;
            object-fit: contain;
            max-width: 100%;
            margin: 0 auto 45px;
        }

        .spies-admin-login-card .fi-sc {
            gap: 20px;
        }

        .spies-admin-login-card .fi-fo-field-label-col,
        .spies-admin-login-card .fi-checkbox-input,
        .spies-admin-login-card .fi-checkbox-input + span {
            display: none;
        }

        .spies-admin-login-card .fi-input-wrp {
            min-height: 48px;
            border: 0 !important;
            border-radius: 20px !important;
            background: #FFF6D7 !important;
            box-shadow: inset 0 4px 4px rgba(0, 0, 0, .22), inset 0 -4px 4px rgba(255, 255, 255, .77) !important;
        }

        .spies-admin-login-card .fi-input-wrp-content-ctn {
            min-height: 48px;
        }

        .spies-admin-login-card input {
            width: 100%;
            height: 48px;
            border: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            color: #16225d !important;
            font-size: 14px !important;
            font-weight: 500;
            padding-inline: 29px !important;
            text-align: left;
        }

        .spies-admin-login-card input::placeholder {
            color: rgba(0, 0, 0, .7);
            font-weight: 500;
            opacity: .7;
        }

        .spies-admin-login-card .fi-input-wrp:focus-within {
            outline: 2px solid rgba(244, 81, 56, .2);
            outline-offset: 2px;
        }

        .spies-admin-login-card .fi-input-wrp-suffix {
            padding-right: 18px;
        }

        .spies-admin-login-card .fi-icon-btn {
            color: #9a9a9a !important;
        }

        .spies-admin-forgot-link {
            color: #16225d;
            font-size: 10px;
            font-weight: 500;
            text-decoration: none;
        }

        .spies-admin-forgot-link:hover {
            color: #f45138;
        }

        .spies-admin-login-card .fi-fo-field-helper-text,
        .spies-admin-login-card .fi-fo-field-wrp-error-message {
            font-size: 10px;
            line-height: 1.25;
        }

        .spies-admin-login-card .fi-fo-field-helper-text {
            display: flex;
            justify-content: flex-end;
            margin-top: 8px;
            padding-right: 22px;
        }

        .spies-admin-login-card .fi-fo-field-wrp-error-message {
            color: #ff0000;
            margin-top: 10px;
        }

        .spies-admin-login-card .fi-ac {
            margin-top: 47px;
        }

        .spies-admin-login-card .fi-btn,
        .spies-admin-login-card .fi-ac-btn-action {
            min-height: 44px;
            border-radius: 10px !important;
            background: #f45138 !important;
            border: .5px solid #dc2626 !important;
            box-shadow: none !important;
            color: #fff !important;
            font-size: 20px !important;
            font-weight: 800 !important;
        }

        .spies-admin-login-card .fi-btn:hover,
        .spies-admin-login-card .fi-btn:focus,
        .spies-admin-login-card .fi-ac-btn-action:hover,
        .spies-admin-login-card .fi-ac-btn-action:focus {
            background: #e5432d !important;
        }

        @media (max-width: 860px) {
            .fi-simple-layout {
                background-size: cover;
            }

            .fi-simple-main {
                width: min(384px, calc(100vw - 32px)) !important;
            }
        }

        @media (max-width: 560px) {
            body.fi-body.fi-panel-admin {
                overflow: auto;
            }

            .fi-simple-main-ctn {
                align-items: center;
                padding: 18px;
            }

            .spies-admin-login-card {
                height: auto;
                min-height: 464px;
                padding: 42px 24px 44px;
            }

            .spies-admin-logo {
                width: 240px;
                height: auto;
                margin-bottom: 40px;
            }

            .spies-admin-login-card .fi-ac {
                margin-top: 44px;
            }
        }
    </style>
@endpush

<div class="spies-admin-login-card">
    <img
        src="{{ asset('assets/images/logo/logo.png') }}"
        alt="Spies Sport"
        class="spies-admin-logo"
    >

    {{ $this->content }}
</div>
