<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; line-height: 1.5; }

        .header { background-color: #464d79; padding: 30px 40px; color: #ffffff; }
        .header-content { display: table; width: 100%; }
        .header-photo { display: table-cell; vertical-align: middle; width: 80px; }
        .header-photo img { width: 70px; height: 70px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.3); }
        .header-info { display: table-cell; vertical-align: middle; padding-left: 20px; }
        .header-name { font-size: 24px; font-weight: bold; margin-bottom: 4px; color: #ffffff; }
        .header-title { font-size: 13px; color: #c8cbdf; margin-bottom: 8px; }
        .header-contact { font-size: 10px; color: #b0b4cc; }
        .header-contact span { margin-right: 15px; }

        .content { padding: 30px 40px; }
        .section { margin-bottom: 22px; }
        .section-title { font-size: 11px; font-weight: bold; color: #464d79; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid #464d79; padding-bottom: 4px; margin-bottom: 10px; }
        .summary { font-size: 11px; color: #555; line-height: 1.6; }

        .exp-item { margin-bottom: 14px; padding-left: 12px; border-left: 2px solid #e5e5e5; }
        .exp-header { display: table; width: 100%; margin-bottom: 3px; }
        .exp-title { font-weight: bold; color: #222; font-size: 11px; }
        .exp-company { color: #464d79; font-size: 10px; font-weight: 600; }
        .exp-period { font-size: 9px; color: #888; float: right; }
        .exp-highlights { padding-left: 14px; margin-top: 5px; }
        .exp-highlights li { margin-bottom: 3px; font-size: 10px; color: #555; }

        .edu-item { margin-bottom: 8px; }
        .edu-degree { font-weight: bold; font-size: 11px; color: #222; }
        .edu-institution { font-size: 10px; color: #555; }
        .edu-year { font-size: 9px; color: #888; float: right; }

        .skills-grid { display: table; width: 100%; }
        .skills-col { display: table-cell; vertical-align: top; width: 50%; padding-right: 15px; }
        .skill-tag { display: inline-block; background: #f3f4f6; color: #333; padding: 3px 8px; border-radius: 3px; font-size: 9px; margin: 2px 3px 2px 0; }
        .cert-item { font-size: 10px; color: #555; margin-bottom: 4px; padding-left: 10px; }
        .cert-item:before { content: "✓ "; color: #4ab098; font-weight: bold; }
        .lang-tag { display: inline-block; border: 1px solid #ddd; padding: 3px 10px; border-radius: 12px; font-size: 9px; margin: 2px 4px 2px 0; }

        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; padding: 10px 40px; font-size: 8px; color: #999; border-top: 1px solid #e5e5e5; }
        .footer a { color: #464d79; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="header-content">
            @if($includeImage && $imageUrl)
            <div class="header-photo">
                <img src="{{ $imageUrl }}" alt="">
            </div>
            @endif
            <div class="header-info" @if(!$includeImage || !$imageUrl) style="padding-left: 0;" @endif>
                <div class="header-name">{{ $resume['name'] ?? '' }}</div>
                @if(!empty($resume['title']))
                    <div class="header-title">{{ $resume['title'] }}</div>
                @endif
                @if(!empty($resume['contact']))
                    <div class="header-contact">
                        @if(!empty($resume['contact']['email']))<span>&#9993; {{ $resume['contact']['email'] }}</span>@endif
                        @if(!empty($resume['contact']['phone']))<span>&#9742; {{ $resume['contact']['phone'] }}</span>@endif
                        @if(!empty($resume['contact']['location']))<span>&#9906; {{ $resume['contact']['location'] }}</span>@endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="content">
        {{-- Summary --}}
        @if(!empty($resume['summary']))
        <div class="section">
            <div class="section-title">Professional Summary</div>
            <p class="summary">{{ $resume['summary'] }}</p>
        </div>
        @endif

        {{-- Experience --}}
        @if(!empty($resume['experience']))
        <div class="section">
            <div class="section-title">Professional Experience</div>
            @foreach($resume['experience'] as $exp)
            <div class="exp-item">
                <div class="exp-header">
                    <span class="exp-title">{{ $exp['title'] ?? '' }}</span>
                    <span class="exp-period">{{ $exp['period'] ?? '' }}</span>
                </div>
                @if(!empty($exp['company']))
                    <div class="exp-company">{{ $exp['company'] }}</div>
                @endif
                @if(!empty($exp['highlights']))
                <ul class="exp-highlights">
                    @foreach($exp['highlights'] as $h)
                        <li>{{ $h }}</li>
                    @endforeach
                </ul>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        {{-- Education --}}
        @if(!empty($resume['education']))
        <div class="section">
            <div class="section-title">Education</div>
            @foreach($resume['education'] as $edu)
            <div class="edu-item">
                <span class="edu-year">{{ $edu['year'] ?? '' }}</span>
                <div class="edu-degree">{{ $edu['degree'] ?? '' }}</div>
                @if(!empty($edu['institution']))
                    <div class="edu-institution">{{ $edu['institution'] }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        {{-- Skills & Certifications --}}
        <div class="skills-grid">
            <div class="skills-col">
                @if(!empty($resume['skills']))
                <div class="section">
                    <div class="section-title">Skills</div>
                    @foreach($resume['skills'] as $skill)
                        <span class="skill-tag">{{ $skill }}</span>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="skills-col">
                @if(!empty($resume['certifications']))
                <div class="section">
                    <div class="section-title">Certifications</div>
                    @foreach($resume['certifications'] as $cert)
                        <div class="cert-item">{{ $cert }}</div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Languages --}}
        @if(!empty($resume['languages']))
        <div class="section">
            <div class="section-title">Languages</div>
            @foreach($resume['languages'] as $lang)
                <span class="lang-tag">{{ $lang }}</span>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Footer --}}
    <div class="footer">
        Powered by <a href="https://vaidyog.com">Vaidyog.com</a>
    </div>
</body>
</html>
