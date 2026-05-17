import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom'
import { useEffect, useState } from 'react'
import Splash from './pages/onboarding/Splash'
import Onboard from './pages/onboarding/Onboard'
import Login from './pages/auth/Login'
import Register from './pages/auth/Register'
import ForgotPassword from './pages/auth/ForgotPassword'
import VerifyEmail from './pages/auth/VerifyEmail'
import OtpVerification from './pages/auth/OtpVerification'
import Kyc from './pages/auth/Kyc'
import Dashboard from './pages/dashboard/Dashboard'
import MakePayment from './pages/payment/MakePayment'
import PaymentSummary from './pages/payment/PaymentSummary'
import SessionPayment from './pages/payment/SessionPayment'
import RequestMoney from './pages/request/RequestMoney'
import ReceiveMoney from './pages/receive/ReceiveMoney'
import ReceiveConfirm from './pages/receive/ReceiveConfirm'
import TransactionList from './pages/transactions/TransactionList'
import TransactionDetail from './pages/transactions/TransactionDetail'
import Notifications from './pages/notifications/Notifications'
import PaymentLinks from './pages/links/PaymentLinks'
import ProtectedRoute from './components/ProtectedRoute'
import Layout from './components/Layout'
import OfflineBanner from './components/OfflineBanner'

export default function App() {
  const [ready, setReady] = useState(false)

  useEffect(() => {
    setReady(true)
  }, [])

  if (!ready) return null

  return (
    <BrowserRouter>
      <OfflineBanner />
      <Routes>
        {/* Onboarding */}
        <Route path="/splash" element={<Splash />} />
        <Route path="/onboard" element={<Onboard />} />

        {/* Auth */}
        <Route path="/auth/login" element={<Login />} />
        <Route path="/auth/register" element={<Register />} />
        <Route path="/auth/forgot-password" element={<ForgotPassword />} />
        <Route path="/auth/verify-email" element={<VerifyEmail />} />
        <Route path="/auth/otp" element={<OtpVerification />} />
        <Route path="/auth/kyc" element={<Kyc />} />

        {/* Protected app routes */}
        <Route element={<ProtectedRoute />}>
          <Route element={<Layout />}>
            <Route path="/dashboard" element={<Dashboard />} />
            <Route path="/pay/scan" element={<MakePayment />} />
            <Route path="/pay/summary" element={<PaymentSummary />} />
            <Route path="/request" element={<RequestMoney />} />
            <Route path="/receive" element={<ReceiveMoney />} />
            <Route path="/links" element={<PaymentLinks />} />
            <Route path="/receive/confirm/:transactionId" element={<ReceiveConfirm />} />
            <Route path="/transactions" element={<TransactionList />} />
            <Route path="/transactions/:id" element={<TransactionDetail />} />
            <Route path="/notifications" element={<Notifications />} />
          </Route>
        </Route>

        {/* Pay by session link (public) */}
        <Route path="/pay/session/:token" element={<SessionPayment />} />

        {/* Default redirect */}
        <Route path="/" element={<Navigate to="/splash" replace />} />
        <Route path="*" element={<Navigate to="/dashboard" replace />} />
      </Routes>
    </BrowserRouter>
  )
}
