@php
    $logoPath = public_path('images/LOGODOMISYL_mobile.png');
    $logoData = base64_encode(file_get_contents($logoPath));

    $backPath=public_path('images/back_2.png');
    $backData = base64_encode(file_get_contents($backPath));

@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bon Cadeau</title>

    <style>
        body {
            font-family: Agency FB, sans-serif;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        .container {
            width: 100%;
            padding: 5px;
        }

        .box {
            width: 100%;
            border-radius: 5px;
            background: #ffffff;
        }

        .colored-box {
            background: #f18f34;
            color: dark;
            padding: 18px;
            border-radius: 10px;
            margin-top: 15px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        /* .header {
            text-align: center;
            padding-bottom: 10px;
        }

        .header img {
            width: 120px;
        } */

        /* .title {
            font-size: 30px;
            font-weight: bold;
            margin-top: 10px;
            color: #333;
        } */

        .section {
            margin-top: 5px;
            padding: 5px;
            background: #fafafa;
            border-left: 4px solid #f18f34;
            border-radius: 6px;
        }

        .label {
            font-weight: bold;
            color: #333;
            font-size: 18px;
        }

        .category-badge {
            font-size: 12px;
            color: #555;
            padding: 6px 5px;
            background: #eee;
            border-radius: 20px;
        }

        .code-box {
            margin-top: 5px;
            background: #fff4d6;
            border-left: 4px solid #f18f34; padding: 20px; border-radius: 8px;
            background-image: url("data:image/jpg;base64,{{ $backData }}");
            background-size: cover;      
            background-position: center;
            background-repeat: no-repeat;
            text-align: center;
        }

        .code {
            font-size: 25px;
            font-weight: bold;
            letter-spacing: 3px;
        }

        .instructions {
            margin-top: 5px;
            font-size:15px;
            background: #eef5ff;
            border-left: 4px solid #1d4ed8;
            border-radius: 6px;
        }
        .prestation-box {
            margin-top: 5px;
            font-size:22px;
            color: white;
            text-align: center;
            position: relative; 
            border-radius: 8px;
        }

        .icon-circle {
            width: 55px;
            height: 55px;
            background: white;
            color: #f18f34;
            font-size: 28px;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .prest-title {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }

        .prest-category {
            display: inline-block;
            margin-top: 8px;
            font-size: 13px;
            background: rgba(255, 255, 255, 0.25);
            padding: 6px 14px;
            border-radius: 20px;
        }

        .prest-desc {
            margin-top: 12px;
            font-size: 14px;
            line-height: 1.4em;
        }

        .prest-divider {
            margin-top: 18px;
            height: 3px;
            width: 100%;
            background: rgba(255,255,255,0.5);
            border-radius: 4px;
        }


        .footer {
            margin-top: 5px;
            text-align: center;
            color: #888;
            font-size: 20px;
        }

        .footer img {
            width: 50px;
            opacity: 0.8;
        }
        /* Bénéficiaire - Mise en valeur */
        .recipient-section {
            text-align: center;
            margin-bottom: 10px;
            padding: 5px;
            background: #fff9f0;
            border-radius: 12px;
            border: 3px dashed #f18f34;
            position: relative;
        }

        .recipient-section::before,
        .recipient-section::after {
            position: absolute;
            color: #f18f34;
            font-size: 24px;
            top: -12px;
        }

        .recipient-section::before {
            left: 20px;
        }

        .recipient-section::after {
            right: 20px;
        }

        .recipient-label {
            font-size: 14px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .recipient-name {
            font-size: 36px;
            color: #f18f34;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }


    </style>
</head>
<body>

<div class="container">
    <div class="box">
        <div style="background: #ffffff; padding: 30px 20px 40px; text-align: center; position: relative;">   
            <div style="position: absolute; left: 30px; top: 30px;">
                <img src="data:image/png;base64,{{ $logoData }}" 
                    alt="Domisyl" 
                    style=" max-width: 100%; height: auto;" />
            </div>
            <h1 style="margin: 0; color: #1e1c1c; font-size: 32px; font-weight: bold;">
                 Bon Cadeau
            </h1>
         </div>


        <div>
            <div class="recipient-section">
                <div class="recipient-label">Pour</div>
                <div class="recipient-name">{{ $cadeau->benef_name }}</div>
                <h3>
                    {{ $cadeau->message ?? '' }}
                </h3>
            </div>
        </div>


        <!-- PRESTATION -->




        <!-- CODE -->
        <div class="code-box">
            <p class="recipient-label">
                {{ $cadeau->carteCadeauService->service->serviceCategory->name }}
            </p>
 <div class="prestation-box">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td style="padding: 5px; background: linear-gradient(135deg, #f9fafb, #ffffff);
                           border-radius: 10px; text-align: center;">

                    <div style="display: inline-flex; align-items: center; gap: 25px;">

                        <!-- SERVICE TITLE (centered) -->
                        <span style="font-size: 25px; font-weight: bold; color: #f18f34;">
                            {{ $cadeau->carteCadeauService->service->title }}
                        </span>
                        <!-- SERVICE CATEGORY (badge) -->
                        <!-- <span style="background-color: #f3f4f6; padding: 6px 14px; 
                                     border-radius: 9999px; color: #6b7280; font-size: 20px; 
                                     font-weight: 600;">
                            
                        </span> -->
                    </div>

                    <p style="margin-top: 5px; color: #4b5563; font-size: 20px;">
                        {{ $cadeau->carteCadeauService->service->serviceCategory->description }}
                    </p>

                </td>
            </tr>
        </table>
        </div>

        <div class="prest-divider"></div>
            <p class="recipient-label">
                Code du bon cadeau
            </p>
            <p class="code">
                {{ $cadeau->code }}
            </p>
        </div>

        <!-- INSTRUCTIONS -->
        <div class="instructions">
            <p class="label"> Comment utiliser votre bon cadeau :</p>
            <ol>
                <li>Aller sur <strong><a href="https://domisyl.groupe-syl.com"></a>https://domisyl.groupe-syl.com</strong></li>
                <li>Cliquez sur « Prendre rendez-vous via carte cadeau »</li>
                <li>Saisir le code reçu</li>
                <li>Choisir une date et un horaire</li>
                <li>Profiter de la prestation</li>
            </ol>
        </div>

        <!-- DATE -->
        <p style="text-align:center; margin-top:15px; font-size:22px;">
            <strong>Valable jusqu'au :</strong> {{ \Carbon\Carbon::parse($cadeau->end_date)->locale('fr')->translatedFormat('d F Y') }}

        </p>

        <!-- FOOTER -->
        <div class="footer">
            <img src="data:image/png;base64,{{ $logoData }}" alt="Domisyl Logo">
            <p>Domisyl</p>
            <p><strong>038 93 684 05 | contact@groupe-syl.com</strong></p>
            <p>Ce bon cadeau est personnel et non remboursable.</p>
        </div>

    </div>

</div>

</body>
</html>
