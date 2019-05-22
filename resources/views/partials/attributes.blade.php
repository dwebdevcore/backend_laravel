@foreach($attributes['keys']['buckets'] as $attribute)
    <span class="attribute">{{$attribute['key']}} <i>{{$attribute['doc_count']}}</i></span>
@endforeach