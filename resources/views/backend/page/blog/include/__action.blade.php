<a href="{{ route('blog-details',$id) }}" target="_blank" data-bs-toggle="tooltip" title=""
   data-bs-original-title="Details Blog" class="round-icon-btn blue-btn">
    <i icon-name="eye"></i>
</a>
<a href="{{ route('admin.page.blog.edit',$id) }}" class="round-icon-btn primary-btn" data-bs-toggle="tooltip" title=""
   data-bs-original-title="Edit Blog">
    <i icon-name="edit-3"></i>
</a>
<button class="round-icon-btn red-btn deleteBlog" type="button" data-id="{{ $id }}" data-bs-toggle="tooltip" title=""
        data-bs-original-title="Delete Blog">
    <i icon-name="trash-2"></i>
</button>
<script>
    "use strict";
    lucide.createIcons()
    $(document).ajaxComplete(function () {
        
        $('[data-bs-toggle="tooltip"]').tooltip({
            "html": true,
        });
    });
</script>
