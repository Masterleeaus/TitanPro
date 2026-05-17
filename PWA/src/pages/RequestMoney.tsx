import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './Page.css';

export default function RequestMoney() {
  const navigate = useNavigate();
  const [from, setFrom] = useState('');
  const [amount, setAmount] = useState('');
  const [note, setNote] = useState('');

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    // TODO: integrate with ZeroPay request-money API via axios
    navigate('/dashboard');
  }

  return (
    <div className="page">
      <header className="page-header">
        <button className="back-btn" onClick={() => navigate(-1)} aria-label="Back">←</button>
        <h1 className="page-title">Request Money</h1>
      </header>

      <div className="page-content">
        <form className="form" onSubmit={handleSubmit}>
          <label className="form-label">
            Request from (email or phone)
            <input
              className="form-input"
              type="text"
              value={from}
              onChange={(e) => setFrom(e.target.value)}
              required
              placeholder="email@example.com"
            />
          </label>
          <label className="form-label">
            Amount
            <input
              className="form-input"
              type="number"
              min="0.01"
              step="0.01"
              value={amount}
              onChange={(e) => setAmount(e.target.value)}
              required
              placeholder="0.00"
            />
          </label>
          <label className="form-label">
            Note (optional)
            <input
              className="form-input"
              type="text"
              value={note}
              onChange={(e) => setNote(e.target.value)}
              placeholder="What's it for?"
            />
          </label>
          <button className="btn btn--primary" type="submit">
            Send Request
          </button>
        </form>
      </div>
    </div>
  );
}
