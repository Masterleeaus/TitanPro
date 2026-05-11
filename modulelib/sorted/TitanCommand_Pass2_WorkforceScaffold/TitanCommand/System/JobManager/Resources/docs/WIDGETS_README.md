# Widgets (with plan gating)
1) Register widget in `Config/<alias>_widgets.php` with keys:
- `class`, `title`, `size`, `locations`, `plans`, `cache_ttl`
2) Render:
```blade
@include('<alias>::widgets.render', ['location'=>'main_dashboard'])
```
