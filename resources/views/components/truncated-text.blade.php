@if ($preview) 
  <div>{!! $preview !!}</div>
@endif
<a href="javascript:;" class="link-more" title="mehr" data-more>
  <x-icons.chevron-down class="icon-chevron-down" />
  <span>mehr</span>
</a>
<div class="js-hidden is-hidden">
  {{ $slot }}
  <a href="javascript:;" class="link-less" title="weniger" data-less>
    <x-icons.chevron-up class="icon-chevron-up" />
    <span>weniger</span>
  </a>
</div>