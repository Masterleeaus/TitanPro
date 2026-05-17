import { Outlet, NavLink } from 'react-router-dom'

const navItems = [
  { to: '/dashboard', label: '🏠', title: 'Home' },
  { to: '/pay/scan', label: '📷', title: 'Pay' },
  { to: '/request', label: '💸', title: 'Request' },
  { to: '/transactions', label: '📋', title: 'History' },
  { to: '/notifications', label: '🔔', title: 'Alerts' },
]

export default function Layout() {
  return (
    <div style={{ display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
      <main style={{ flex: 1, paddingBottom: '64px' }}>
        <Outlet />
      </main>
      <nav style={{
        position: 'fixed', bottom: 0, left: 0, right: 0,
        background: '#fff', borderTop: '1px solid #eee',
        display: 'flex', justifyContent: 'space-around',
        padding: '8px 0', zIndex: 100,
      }}>
        {navItems.map(({ to, label, title }) => (
          <NavLink
            key={to}
            to={to}
            title={title}
            style={({ isActive }) => ({
              display: 'flex', flexDirection: 'column', alignItems: 'center',
              fontSize: '20px', textDecoration: 'none',
              opacity: isActive ? 1 : 0.5,
              color: '#1a1a2e',
            })}
          >
            <span>{label}</span>
            <span style={{ fontSize: '10px' }}>{title}</span>
          </NavLink>
        ))}
      </nav>
    </div>
  )
}
