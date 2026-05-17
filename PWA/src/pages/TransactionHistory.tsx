import { useNavigate } from 'react-router-dom';
import './Page.css';

interface Transaction {
  id: string;
  type: 'sent' | 'received';
  amount: number;
  counterparty: string;
  date: string;
  note?: string;
}

const MOCK_TRANSACTIONS: Transaction[] = [];

export default function TransactionHistory() {
  const navigate = useNavigate();

  return (
    <div className="page">
      <header className="page-header">
        <button className="back-btn" onClick={() => navigate(-1)} aria-label="Back">←</button>
        <h1 className="page-title">Transaction History</h1>
      </header>

      <div className="page-content">
        {MOCK_TRANSACTIONS.length === 0 ? (
          <p className="empty-state">No transactions yet.</p>
        ) : (
          <ul className="transaction-list">
            {MOCK_TRANSACTIONS.map((tx) => (
              <li key={tx.id} className="transaction-item">
                <div className="transaction-icon">
                  {tx.type === 'sent' ? '↑' : '↓'}
                </div>
                <div className="transaction-details">
                  <p className="transaction-counterparty">{tx.counterparty}</p>
                  {tx.note && <p className="transaction-note">{tx.note}</p>}
                  <p className="transaction-date">{tx.date}</p>
                </div>
                <p className={`transaction-amount transaction-amount--${tx.type}`}>
                  {tx.type === 'sent' ? '-' : '+'}${tx.amount.toFixed(2)}
                </p>
              </li>
            ))}
          </ul>
        )}
      </div>

      <nav className="bottom-nav">
        <a href="/dashboard" className="bottom-nav-item">Home</a>
        <a href="/history" className="bottom-nav-item active">History</a>
        <a href="/qr" className="bottom-nav-item">Scan</a>
        <a href="/profile" className="bottom-nav-item">Profile</a>
      </nav>
    </div>
  );
}
