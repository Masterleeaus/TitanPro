import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './Page.css';

export default function Login() {
  const navigate = useNavigate();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    // TODO: integrate with ZeroPay auth API via axios
    navigate('/dashboard');
  }

  return (
    <div className="page page--centered">
      <div className="auth-card">
        <h1 className="auth-title">ZeroPay</h1>
        <p className="auth-subtitle">Sign in to your account</p>
        <form className="form" onSubmit={handleSubmit}>
          <label className="form-label">
            Email
            <input
              className="form-input"
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              autoComplete="email"
            />
          </label>
          <label className="form-label">
            Password
            <input
              className="form-input"
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              autoComplete="current-password"
            />
          </label>
          <button className="btn btn--primary" type="submit">
            Sign In
          </button>
        </form>
      </div>
    </div>
  );
}
