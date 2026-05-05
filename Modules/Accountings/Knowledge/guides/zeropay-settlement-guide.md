# External ZeroPay Boundary

ZeroPay is a separate payment system. Titan Money may prepare invoice context for ZeroPay, but it does not implement payment rails or settlement logic.

When ZeroPay status is needed, consume it as external invoice status context only after the integration is explicitly connected.
