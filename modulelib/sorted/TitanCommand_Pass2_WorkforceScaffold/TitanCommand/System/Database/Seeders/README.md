# Channels Agent Demo Data Seeder

This seeder creates fake agents and demo sub-items for testing the Channels Agent extension.

## What it creates

### 3 Demo Agents:
1. **TechVision AI - Product Updates**
   - Tech startup focused on AI automation
   - 2 containers
   - Professional tone
   - 5 demo sub-items (product updates, tips, engagement, educational)

2. **Luxe Fashion Co. - Spring Collection**
   - Fashion brand with sustainable focus
   - 3 containers
   - Friendly tone
   - 6 demo sub-items (promotional, lifestyle, behind-the-scenes)

3. **FitLife Coach - Daily Motivation**
   - Fitness coaching and motivation
   - 2 containers
   - Enthusiastic tone
   - 5 demo sub-items (motivational, tips, educational, engagement)

### Sub-item Statuses:
Sub-items are created with varied statuses to test different scenarios:
- `pending_approval` - Sub-items waiting for approval
- `confirmed` - Sub-items that have been confirmed
- `scheduled` - Sub-items scheduled for confirming

## Prerequisites

Before running the seeder, ensure:
1. At least one user exists in the database
2. The user has connected channels containers (via Channels extension)

## Usage

Run the seeder using the Artisan command:

```bash
php artisan social-media-agent:seed-demo-data
```

## What the seeder does

1. Finds the first user in the database
2. Gets the user's connected channels containers
3. Creates 3 diverse agents with different:
   - Business types
   - Target audiences
   - Sub-item types and tones
   - Scheduling preferences
4. Creates 5-6 demo sub-items for each agent with:
   - Realistic content
   - Hashtags
   - Different statuses
   - Scheduled times spread over the next few days

## Notes

- Sub-items are created with timestamps spread over the past week (creation date)
- Scheduled times are spread over the next several days
- Each agent uses a subset of the user's available containers
- The seeder is safe to run multiple times (it will create additional demo data each time)

## Testing Scenarios

This demo data allows you to test:
- ✅ Viewing agents list
- ✅ Viewing pending sub-items
- ✅ Approving sub-items
- ✅ Rejecting sub-items
- ✅ Bulk operations
- ✅ Statistics and analytics
- ✅ Different sub-item types and tones
- ✅ Multi-container management

## Cleanup

To remove demo data, manually delete agents from the UI or database:
- Delete agents from `ext_social_media_agents` table
- Related sub-items will be automatically removed via cascade

## Troubleshooting

**Error: "No users found"**
- Solution: Create at least one user in your system first

**Error: "No channels containers found"**
- Solution: Connect at least one channels container using the Channels extension
