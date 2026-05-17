import { useNavigate } from 'react-router-dom';
import './Page.css';

export default function Profile() {
  const navigate = useNavigate();

  return (
    <div className="page">
      <header className="page-header">
        <button className="back-btn" onClick={() => navigate(-1)} aria-label="Back">←</button>
        <h1 className="page-title">Profile</h1>
      </header>

      <div className="page-content">
        <div className="profile-avatar">👤</div>
        <p className="profile-name">ZeroPay User</p>
        <p className="profile-email">user@example.com</p>

        <ul className="settings-list">
          <li className="settings-item">Edit Profile</li>
          <li className="settings-item">Security</li>
          <li className="settings-item">Linked Accounts</li>
          <li className="settings-item">Notifications</li>
          <li className="settings-item settings-item--danger">Sign Out</li>
        </ul>
      </div>

      <nav className="bottom-nav">
        <a href="/dashboard" className="bottom-nav-item">Home</a>
        <a href="/history" className="bottom-nav-item">History</a>
        <a href="/qr" className="bottom-nav-item">Scan</a>
        <a href="/profile" className="bottom-nav-item active">Profile</a>
      </nav>
    </div>
  );
}
