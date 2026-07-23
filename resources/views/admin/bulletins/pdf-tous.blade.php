<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <style>
        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

@foreach ($bulletins as $index => $item)

    @include($vue, [
        'eleve'         => $item['eleve'],
        'bulletin'      => $item['bulletin'],
        'periode'       => $item['periode'],
        'classe'        => $classe,
        'etablissement' => $etablissement,
        'lang'          => $lang,
    ])

    @if(!$loop->last)
        <div class="page-break"></div>
    @endif

@endforeach

</body>
</html>