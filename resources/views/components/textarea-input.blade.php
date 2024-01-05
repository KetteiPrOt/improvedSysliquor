<textarea
    {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm resize-y max-h-64 min-h-[50px]']) !!}
>{{$slot}}</textarea>