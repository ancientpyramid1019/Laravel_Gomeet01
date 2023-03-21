@can('support-ticket-action')
    <a href="{{ route('admin.ticket.show',$uuid) }}" class="round-icon-btn primary-btn" data-bs-toggle="tooltip"
       title="" data-bs-original-title="Ticket Details"><i icon-name="eye"></i></a>
    <script>
        'use strict';
        lucide.createIcons();
    </script>
@endcan

