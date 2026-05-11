# Customer Hub Chatbot Map

Internal (`chatbot.sql`)
- Titan Zero — orchestrator and hub brain
- Complaint Manager — escalations and retention
- Reports — KPI and insight assistant

External (`ext_chatbots.sql`)
- Customer Assistant — general omni assistant
- Booking Bot — appointment capture
- Quote Bot — quote intake
- FAQ Bot — common questions
- Complaint Handler — issue capture
- Lead Qualifier — lead triage
- Job Tracker — after-booking status

Recommended Customer Hub use
- Omni Box defaults to Customer Assistant with Titan Zero escalation
- Active Assistants should surface Complaint Manager + Reports internally
- Campaign / CRM cards can call Lead Qualifier and Quote Bot as external flows
