<vmap:VMAP
    xmlns:vmap="http://www.iab.net/videosuite/vmap"
    version="1.0"
>
    @foreach ($prerolls as $i => $item)
        <vmap:AdBreak
            timeOffset="start"
            breakType="{{ $item['break_type'] }}"
            breakId="{{ $item['id'] }}"
        >
            <vmap:AdSource
                id="{{ $item['id'] }}-source"
                allowMultipleAds="true"
                followRedirects="true"
            >
                <vmap:AdTagURI templateType="vast3">
                    <![CDATA[ {!! $item['vast_url'] !!} ]]>
                </vmap:AdTagURI>
            </vmap:AdSource>
        </vmap:AdBreak>
    @endforeach

    @foreach ($midrolls as $i => $item)
        <vmap:AdBreak
            timeOffset="{{ $item['time_offset'] }}"
            breakType="{{ $item['break_type'] }}"
            breakId="{{ $item['id'] }}"
        >
            <vmap:AdSource
                id="{{ $item['id'] }}-source"
                allowMultipleAds="true"
                followRedirects="true"
            >
                <vmap:AdTagURI templateType="vast3">
                    <![CDATA[ {!! $item['vast_url'] !!} ]]>
                </vmap:AdTagURI>
            </vmap:AdSource>
        </vmap:AdBreak>
    @endforeach

    @foreach ($postrolls as $item)
        <vmap:AdBreak
            timeOffset="end"
            breakType="{{ $item['break_type'] }}"
            breakId="{{ $item['id'] }}"
        >
            <vmap:AdSource
                id="{{ $item['break_type'] }}-source"
                allowMultipleAds="true"
                followRedirects="true"
            >
                <vmap:AdTagURI templateType="vast3">
                    <![CDATA[ {!! $item['vast_url'] !!} ]]>
                </vmap:AdTagURI>
            </vmap:AdSource>
        </vmap:AdBreak>
    @endforeach

</vmap:VMAP>
