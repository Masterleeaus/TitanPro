# AI-Native Module Guide

Each Titan module can ship domain knowledge and one or more module agents.

| Layer | Responsibility |
|---|---|
| TitanCore | provider routing, embeddings, vector search, memory, usage, policy middleware |
| TitanZero | system AI, configuration, governance, diagnostics, escalation |
| TitanAgents | module agent builder, training/binding, evals, deployment |
| Module | domain files, workflows, actions, tools, permissions |

Lifecycle: add files to Knowledge, approve them in dataset-manifest, TitanCore indexes them, TitanAgents binds the agent, TitanZero monitors and configures.
