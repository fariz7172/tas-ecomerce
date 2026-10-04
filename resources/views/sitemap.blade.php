{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    
    <!-- Homepage / 3D Atelier -->
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Product URLs for Google Indexing -->
    @foreach($products as $prod)
    <url>
        <loc>{{ url('/#katalog') }}</loc>
        <lastmod>{{ $prod->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
        @if($prod->images->count() > 0)
            @foreach($prod->images as $img)
            <image:image>
                <image:loc>{{ asset($img->image_path) }}</image:loc>
                <image:title>{{ $img->alt_text }}</image:title>
                <image:caption>{{ $prod->name }} - {{ $prod->material }}</image:caption>
            </image:image>
            @endforeach
        @endif
    </url>
    @endforeach

</urlset>
