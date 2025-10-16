<li>
    <a href="{{ route('groups.index', ['group_id' => $cat->id]) }}"
       class="{{ $groupId == $cat->id ? 'active' : '' }}">
        {{ $cat->id_parent ? '• ' : '' }}{{ $cat->name }}
    </a>
    ({{ $totalCounts[$cat->id] ?? 0 }})

    @if(!empty($expanded[$cat->id]))
        @php
            $children = $allGroups->where('id_parent', $cat->id);
        @endphp

        @if($children->count())
            <ul style="margin-left: 15px;">
                @foreach($children as $child)
                    @include('groups.partials.category_node', [
                        'cat' => $child,
                        'allGroups' => $allGroups,
                        'totalCounts' => $totalCounts,
                        'expanded' => $expanded,
                        'groupId' => $groupId
                    ])
                @endforeach
            </ul>
        @endif
    @endif
</li>
