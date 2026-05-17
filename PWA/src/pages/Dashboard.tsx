import { Link } from 'react-router-dom';
import './Page.css';

export default function Dashboard() {
  return (
    <div className="page">
      <header className="page-header">
        <h1 className="page-title">ZeroPay</h1>
        <Link to="/notifications" className="icon-btn" aria-label="Notifications">
          🔔
        </Link>
      </header>

      <section className="balance-card">
        <p className="balance-label">Available Balance</p>
        <p className="balance-amount">$0.00</p>
      </section>

      <nav className="quick-actions">
        <Link to="/send" className="action-btn">
          <span className="action-icon">↑</span>
          <span>Send</span>
        </Link>
        <Link to="/request" className="action-btn">
          <span className="action-icon">↓</span>
          <span>Request</span>
        </Link>
        <Link to="/qr" className="action-btn">
          <span className="action-icon">⊞</span>
          <span>QR Pay</span>
        </Link>
        <Link to="/history" className="action-btn">
          <span className="action-icon">☰</span>
          <span>History</span>
        </Link>
      </nav>

      <section className="section">
        <h2 className="section-title">Recent Transactions</h2>
        <p className="empty-state">No recent transactions.</p>
      </section>

      <nav className="bottom-nav">
        <Link to="/dashboard" className="bottom-nav-item active">Home</Link>
        <Link to="/history" className="bottom-nav-item">History</Link>
        <Link to="/qr" className="bottom-nav-item">Scan</Link>
        <Link to="/profile" className="bottom-nav-item">Profile</Link>
      </nav>
    </div>
  );
}
