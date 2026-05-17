This pass adds Templates as a first-class page instead of replacing Drafts/Gallery entirely.

Changes:
- Added dashboard.user.quotemaker.templates route
- Added QuoteMakerController::templates()
- Reused gallery-page pattern to create a Templates page
- Replaced sidebar Recent Drafts section on index with Templates
- Kept gallery/drafts page available for saved outputs
