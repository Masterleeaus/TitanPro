This pass keeps the card system merge-safe while making it more operational.

Notes:
- No outer layout assumptions changed.
- Existing card shells remain intact.
- New Alpine state is local to work-ops-cards.blade.php.
- Inline mini-wizards are UI/action-ready and can be mapped to backend endpoints later.
