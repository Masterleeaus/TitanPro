import { useState, useRef, ChangeEvent } from 'react'
import { useNavigate } from 'react-router-dom'
import { authApi, KycPayload } from '../../api/auth'

const ID_TYPES = ['Passport', 'Driver Licence', 'National ID']

export default function Kyc() {
  const navigate = useNavigate()

  const [firstName, setFirstName] = useState('')
  const [lastName, setLastName] = useState('')
  const [country, setCountry] = useState('')
  const [city, setCity] = useState('')
  const [zipCode, setZipCode] = useState('')
  const [idType, setIdType] = useState(ID_TYPES[0])
  const [frontFile, setFrontFile] = useState<File | null>(null)
  const [backFile, setBackFile] = useState<File | null>(null)
  const [frontPreview, setFrontPreview] = useState<string | null>(null)
  const [backPreview, setBackPreview] = useState<string | null>(null)
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)

  const frontRef = useRef<HTMLInputElement>(null)
  const backRef = useRef<HTMLInputElement>(null)

  const handleFileChange = (
    e: ChangeEvent<HTMLInputElement>,
    setter: (f: File | null) => void,
    previewSetter: (p: string | null) => void,
  ) => {
    const file = e.target.files?.[0] ?? null
    setter(file)
    if (file) {
      const reader = new FileReader()
      reader.onload = () => previewSetter(reader.result as string)
      reader.readAsDataURL(file)
    } else {
      previewSetter(null)
    }
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    if (!frontFile) {
      setError('Please upload the front of your ID document.')
      return
    }
    setLoading(true)
    setError('')
    try {
      const payload: KycPayload = {
        first_name: firstName,
        last_name: lastName,
        country,
        city,
        zip_code: zipCode,
        id_type: idType,
        id_front: frontFile,
      }
      if (backFile) payload.id_back = backFile
      await authApi.submitKyc(payload)
      navigate('/dashboard', { replace: true })
    } catch (err: unknown) {
      const message =
        err instanceof Error ? err.message : 'KYC submission failed. Please try again.'
      setError(message)
    } finally {
      setLoading(false)
    }
  }

  return (
    <div style={pageStyle}>
      <div style={cardStyle}>
        <div style={{ textAlign: 'center', marginBottom: '24px' }}>
          <div style={{ fontSize: '48px' }}>🪪</div>
          <h1 style={{ fontSize: '22px', fontWeight: 700, color: '#1a1a2e', margin: '8px 0 4px' }}>
            Identity verification
          </h1>
          <p style={{ color: '#666', fontSize: '14px', lineHeight: 1.5 }}>
            To comply with financial regulations we need to verify your identity.
            Your data is encrypted and never shared with third parties.
          </p>
        </div>

        {error && <div style={errorStyle}>{error}</div>}

        <form onSubmit={handleSubmit}>
          <div style={rowStyle}>
            <div style={{ flex: 1 }}>
              <label htmlFor="firstName" style={labelStyle}>First name</label>
              <input
                id="firstName"
                type="text"
                value={firstName}
                onChange={(e) => setFirstName(e.target.value)}
                required
                placeholder="Jane"
                style={inputStyle}
              />
            </div>
            <div style={{ flex: 1 }}>
              <label htmlFor="lastName" style={labelStyle}>Last name</label>
              <input
                id="lastName"
                type="text"
                value={lastName}
                onChange={(e) => setLastName(e.target.value)}
                required
                placeholder="Smith"
                style={inputStyle}
              />
            </div>
          </div>

          <div style={fieldStyle}>
            <label htmlFor="country" style={labelStyle}>Country</label>
            <input
              id="country"
              type="text"
              value={country}
              onChange={(e) => setCountry(e.target.value)}
              required
              placeholder="Australia"
              style={inputStyle}
            />
          </div>

          <div style={rowStyle}>
            <div style={{ flex: 1 }}>
              <label htmlFor="city" style={labelStyle}>City</label>
              <input
                id="city"
                type="text"
                value={city}
                onChange={(e) => setCity(e.target.value)}
                required
                placeholder="Sydney"
                style={inputStyle}
              />
            </div>
            <div style={{ flex: 1 }}>
              <label htmlFor="zipCode" style={labelStyle}>Post / ZIP code</label>
              <input
                id="zipCode"
                type="text"
                value={zipCode}
                onChange={(e) => setZipCode(e.target.value)}
                required
                placeholder="2000"
                style={inputStyle}
              />
            </div>
          </div>

          <div style={fieldStyle}>
            <label htmlFor="idType" style={labelStyle}>ID document type</label>
            <select
              id="idType"
              value={idType}
              onChange={(e) => setIdType(e.target.value)}
              style={{ ...inputStyle, appearance: 'auto' }}
            >
              {ID_TYPES.map((t) => (
                <option key={t} value={t}>{t}</option>
              ))}
            </select>
          </div>

          {/* Front of ID */}
          <div style={fieldStyle}>
            <label style={labelStyle}>
              {idType === 'Passport' ? 'Photo page' : 'Front of document'}
            </label>
            <input
              ref={frontRef}
              type="file"
              accept="image/*"
              style={{ display: 'none' }}
              onChange={(e) => handleFileChange(e, setFrontFile, setFrontPreview)}
            />
            <button
              type="button"
              onClick={() => frontRef.current?.click()}
              style={uploadBtnStyle}
            >
              {frontPreview ? '📷 Change front image' : '📷 Upload front of ID'}
            </button>
            {frontPreview && (
              <img src={frontPreview} alt="ID front preview" style={previewStyle} />
            )}
          </div>

          {/* Back of ID (not required for passport) */}
          {idType !== 'Passport' && (
            <div style={fieldStyle}>
              <label style={labelStyle}>Back of document</label>
              <input
                ref={backRef}
                type="file"
                accept="image/*"
                style={{ display: 'none' }}
                onChange={(e) => handleFileChange(e, setBackFile, setBackPreview)}
              />
              <button
                type="button"
                onClick={() => backRef.current?.click()}
                style={uploadBtnStyle}
              >
                {backPreview ? '📷 Change back image' : '📷 Upload back of ID'}
              </button>
              {backPreview && (
                <img src={backPreview} alt="ID back preview" style={previewStyle} />
              )}
            </div>
          )}

          <button type="submit" disabled={loading} style={{ ...btnStyle, marginTop: '8px' }}>
            {loading ? 'Submitting…' : 'Submit for Verification'}
          </button>
        </form>

        <p style={{ textAlign: 'center', marginTop: '16px', color: '#666', fontSize: '13px' }}>
          You can also{' '}
          <button
            type="button"
            style={{ background: 'none', border: 'none', color: '#e94560', cursor: 'pointer', fontWeight: 600, fontSize: '13px', padding: 0 }}
            onClick={() => navigate('/dashboard', { replace: true })}
          >
            skip for now
          </button>{' '}
          and complete this later.
        </p>
      </div>
    </div>
  )
}

const pageStyle: React.CSSProperties = {
  minHeight: '100vh',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  background: '#f5f5f5',
  padding: '16px',
}

const cardStyle: React.CSSProperties = {
  background: '#fff',
  borderRadius: '16px',
  padding: '32px 24px',
  width: '100%',
  maxWidth: '480px',
  boxShadow: '0 2px 24px rgba(0,0,0,0.08)',
}

const fieldStyle: React.CSSProperties = { marginBottom: '16px' }

const rowStyle: React.CSSProperties = {
  display: 'flex',
  gap: '12px',
  marginBottom: '16px',
}

const labelStyle: React.CSSProperties = {
  display: 'block',
  fontWeight: 600,
  marginBottom: '6px',
  fontSize: '14px',
  color: '#1a1a2e',
}

const inputStyle: React.CSSProperties = {
  width: '100%',
  border: '1px solid #ddd',
  borderRadius: '8px',
  padding: '10px 12px',
  fontSize: '15px',
  outline: 'none',
  boxSizing: 'border-box',
  color: '#1a1a2e',
}

const btnStyle: React.CSSProperties = {
  width: '100%',
  background: '#e94560',
  color: '#fff',
  border: 'none',
  borderRadius: '10px',
  padding: '14px',
  fontSize: '16px',
  fontWeight: 600,
  cursor: 'pointer',
}

const uploadBtnStyle: React.CSSProperties = {
  width: '100%',
  background: '#f8f8f8',
  border: '2px dashed #ddd',
  borderRadius: '10px',
  padding: '12px',
  fontSize: '14px',
  cursor: 'pointer',
  color: '#444',
  fontWeight: 600,
  textAlign: 'center',
}

const previewStyle: React.CSSProperties = {
  width: '100%',
  borderRadius: '8px',
  marginTop: '8px',
  objectFit: 'cover',
  maxHeight: '180px',
  border: '1px solid #eee',
}

const errorStyle: React.CSSProperties = {
  background: '#fff0f0',
  border: '1px solid #ffcccc',
  borderRadius: '8px',
  padding: '10px 14px',
  color: '#cc0000',
  fontSize: '14px',
  marginBottom: '16px',
}
