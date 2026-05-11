# Bookings Hub Chatbot Map

Internal (`chatbot.sql`)
- Titan Zero — orchestrator
- Scheduler — internal scheduling assistant
- Reports — booking/capacity insights

External (`ext_chatbots.sql`)
- Customer Assistant — omni intake
- Booking Bot — booking capture and reschedule
- Job Tracker — appointment/status updates

Recommended Bookings Hub use
- Calendar and capacity cards should surface Scheduler internally
- External booking surfaces should launch Booking Bot flows
- Customer Assistant can sit in omni intake for general requests
