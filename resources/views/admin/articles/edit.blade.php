{{--
    edit.blade.php — delegates to the shared create/edit form
    The create.blade.php already handles both create ($article = null) and edit ($article = model)
--}}
@include('admin.articles.create')
