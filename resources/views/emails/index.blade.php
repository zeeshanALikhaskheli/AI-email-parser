<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emails</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="https://leadport.org/storage/logos/app/C42566KV6A96FA6698N2T59K4TP6W372ZB7KCVV5logo-small.png" type="image/x-icon">

    <!-- Google Translate Script -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,ur,es,fr,de,ar', // Add other language codes as needed
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE, // Change to HORIZONTAL layout
                autoDisplay: false, // Disable automatic translation bar
                callback: function() {
                    setTimeout(function() {
                        var translateElement = document.getElementById("google_translate_element");
                        if (translateElement) {
                            translateElement.style.display = "none"; // Hide the translate bar
                        }

                        var translateToolbar = document.getElementById("google_translate_element").getElementsByClassName("goog-te-banner-frame")[0];
                        if (translateToolbar) {
                            translateToolbar.style.display = "none";
                        }
                    }, 1000);
                }
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
        }

        h1 {
            color: #23D26B;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .email-card {
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            padding: 15px;
            transition: transform 0.2s ease;
        }

        .email-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            cursor: pointer;
        }

        .email-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .email-header h5 {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .email-header small {
            color: #666;
            font-size: 0.9rem;
        }

        .read-more-toggle {
            color: #23D26B;
            cursor: pointer;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .read-more-toggle:hover {
            color: #0056b3;
        }

        .modal-body {
            white-space: pre-wrap;
            word-wrap: break-word;
            padding: 20px;
            background: #fafafa;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .read-more-content {
            display: none;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .read-more-short {
            display: inline;
        }

        .email-body {
            font-size: 0.95rem;
            color: #555;
        }

        .btn-primary {
            background-color: #23D26B;
            border: none;
        }

        .btn-primary:hover {
            background-color: #23d26cc4;
        }

        #google_translate_element {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 999;
        }

        /* Custom flag style */
        .goog-te-gadget .goog-te-combo {
            background-image: url('https://flagpedia.net/data/flags/h80/us.png'); /* Set the default flag image */
            background-repeat: no-repeat;
            background-position: left center;
            padding-left: 30px;
        }

        .goog-te-gadget .goog-te-combo option[value="en"] {
            background-image: url('https://flagpedia.net/data/flags/h80/us.png'); /* US flag */
        }

        .goog-te-gadget .goog-te-combo option[value="ur"] {
            background-image: url('https://flagpedia.net/data/flags/h80/pk.png'); /* Pakistan flag */
        }

        .goog-te-gadget .goog-te-combo option[value="es"] {
            background-image: url('https://flagpedia.net/data/flags/h80/es.png'); /* Spain flag */
        }

        .goog-te-gadget .goog-te-combo option[value="fr"] {
            background-image: url('https://flagpedia.net/data/flags/h80/fr.png'); /* France flag */
        }

        .goog-te-gadget .goog-te-combo option[value="de"] {
            background-image: url('https://flagpedia.net/data/flags/h80/de.png'); /* Germany flag */
        }

        .goog-te-gadget .goog-te-combo option[value="ar"] {
            background-image: url('https://flagpedia.net/data/flags/h80/sa.png'); /* Saudi Arabia flag */
        }
    </style>
</head>
<body>
    <!-- Google Translate Widget -->
    <div id="google_translate_element"></div>

    <div class="container my-5">
        <h1 class="text-center">Emails  <span class="btn btn-primary">{{$totalEmails}}   </span> </h1>

        @if(count($emails) > 0)
            <div class="email-list">
                @foreach ($emails as $email)
                    <div class="email-card">
                        <div class="email-header">
                            <h5>{{ $email->subject }}</h5>
                            <small>{{ $email->date }}</small>
                        </div>
                        <p><strong>From:</strong> {{ $email->from }}</p>
                        <div class="email-body">
                            <span class="read-more-short">{{ Str::limit(strip_tags($email->body), 100, '...') }}</span>
                            <span class="read-more-content">{{ strip_tags($email->body, '<p><b><i><u>') }}</span>
                            <span class="read-more-toggle">Read More</span>
                        </div>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#emailModal{{ $loop->index }}">View Logistics Data</button>
                    </div>
                    <div class="modal fade" id="emailModal{{ $loop->index }}" tabindex="-1" aria-labelledby="emailModalLabel{{ $loop->index }}" aria-hidden="true">
   
   
                        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel{{ $loop->index }}">Logistics Data:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Check if logistics data is available -->
                @if(!empty($email->logistics_data))
                    <!-- If logistics data is available, display it in a table -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(explode("\n", str_replace('\n', "\n", $email->logistics_data)) as $line)
                                @php
                                    $parts = explode(':', $line, 2);
                                @endphp
                                @if(count($parts) === 2)
                                    <tr>
                                        <td><strong>{{ trim($parts[0]) }}</strong></td>
                                        <td>{{ trim($parts[1]) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <!-- If no logistics data, display a message -->
                    <p><strong>Logistics Data is missing</strong></p>
                @endif
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

                    
                @endforeach
            </div>
        @else
            <div class="alert alert-info text-center">
                No emails found.
            </div>
        @endif
    </div>

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggles = document.querySelectorAll('.read-more-toggle');
            toggles.forEach(toggle => {
                toggle.addEventListener('click', function () {
                    const parent = this.closest('.email-body');
                    const shortContent = parent.querySelector('.read-more-short');
                    const fullContent = parent.querySelector('.read-more-content');
                    
                    if (fullContent.style.display === 'none' || fullContent.style.display === '') {
                        fullContent.style.display = 'inline-block';
                        shortContent.style.display = 'none';
                        this.textContent = 'Read Less';
                    } else {
                        fullContent.style.display = 'none';
                        shortContent.style.display = 'inline';
                        this.textContent = 'Read More';
                    }
                });
            });
        });
    </script>
</body>
</html>
