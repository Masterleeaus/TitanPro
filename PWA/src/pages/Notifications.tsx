import { useNavigate } from 'react-router-dom';
import './Page.css';

export default function Notifications() {
  const navigate = useNavigate();

  return (
    <div className="page">
      <header className="page-header">
        <button className="back-btn" onClick={() => navigate(-1)} aria-label="Back">←</button>
        <h1 className="page-title">Notifications</h1>
      </header>

      <div className="page-content">
        <p className="empty-state">No notifications.</p>
      </div>
    </div>
  );
}
