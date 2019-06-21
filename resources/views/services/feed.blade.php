<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ config('blog.title') }}</title>
        <description>{{ config('blog.description') }}</description>
        <link>{{ url('/') }}</link>
        <atom:link href="{{ route('rss') }}" rel="self" type="application/rss+xml"/>
        <pubDate>{{ $posts[0]->updated_at->format(DATE_RSS) }}</pubDate>
        <lastBuildDate>{{ $posts[0]->updated_at->format(DATE_RSS) }}</lastBuildDate>
        <generator>{{config('blog.author')}}</generator>
        @foreach ($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ route('blog.view',$post->id) }}</link>
                <description>{{ strip_tags(str_limit($post->content)) }}</description>
                <pubDate>{{ $post->created_at->format(DATE_RSS)}}</pubDate>
                <author>{{ env('MAIL_FROM') }}({{ config('blog.author')}})</author>
                <guid>{{ route('blog.view',$post->id) }}</guid>
                @foreach($post->category as $cate)
                <category>{{ $cate }}</category>
                @endforeach
            </item>
        @endforeach
    </channel>
</rss>