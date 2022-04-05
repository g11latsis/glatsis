<?php load_view('header', ['page_title' => 'Dashboard']); ?>
<?php load_view('navigation', $data); ?>
<div id="main-content">
<?php if (!empty($data['view'])): ?>
<?php load_view($data['view'], $data); ?>
<?php endif; ?>
</div>

<?php load_view('footer'); ?>