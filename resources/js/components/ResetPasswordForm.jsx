import React, { useState } from 'react';
import { useParams } from 'react-router-dom';

function ResetPasswordForm() {
    const { token } = useParams();
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setPasswordConfirmation] = useState('');
    const [errors, setErrors] = useState({});
    const [status, setStatus] = useState(null);
    const [passwordVisible, setPasswordVisible] = useState(false);
    const [confirmPasswordVisible, setConfirmPasswordVisible] = useState(false);

    const handleSubmit = (event) => {
        event.preventDefault();
        const formData = { token, email, password, password_confirmation };

        fetch('/reset-password', {
            method: 'POST',
            body: JSON.stringify(formData),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            setStatus(data.status);
            setErrors(data.errors || {});
            // Không cần navigate ở đây vì backend sẽ redirect
        })
        .catch(error => {
            console.error('Lỗi:', error);
            setErrors({ general: 'Đã có lỗi xảy ra. Vui lòng thử lại.' });
            setStatus(null);
        });
    };

    const togglePasswordVisibility = () => {
        setPasswordVisible(!passwordVisible);
    };

    const toggleConfirmPasswordVisibility = () => {
        setConfirmPasswordVisible(!confirmPasswordVisible);
    };

    return (
        <div className="reset-container">
            <div className="card-header">{ __('Đặt Lại Mật Khẩu') }</div>
            <div className="card-body">
                {status && (
                    <div className="alert alert-success" role="alert">
                        {status}
                    </div>
                )}

                {errors.general && (
                    <div className="alert alert-danger" role="alert">
                        {errors.general}
                    </div>
                )}

                <form onSubmit={handleSubmit}>
                    <input type="hidden" name="token" value={token} />

                    <div className="form-group">
                        <label htmlFor="email">{ __('Địa chỉ Email') }</label>
                        <input
                            type="email"
                            className={`form-control ${errors.email ? 'is-invalid' : ''}`}
                            id="email"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            required
                            autoComplete="email"
                        />
                        {errors.email && (
                            <span className="invalid-feedback" role="alert">
                                <strong>{errors.email}</strong>
                            </span>
                        )}
                    </div>

                    <div className="form-group">
                        <label htmlFor="password">{ __('Mật Khẩu Mới') }</label>
                        <div className="password-wrapper">
                            <input
                                type={passwordVisible ? 'text' : 'password'}
                                className={`form-control ${errors.password ? 'is-invalid' : ''}`}
                                id="password"
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                required
                                autoComplete="new-password"
                            />
                            <span className="toggle-password" onClick={togglePasswordVisibility}>
                                {passwordVisible ? '🙈' : '👁️'}
                            </span>
                        </div>
                        {errors.password && (
                            <span className="invalid-feedback" role="alert">
                                <strong>{errors.password}</strong>
                            </span>
                        )}
                    </div>

                    <div className="form-group">
                        <label htmlFor="password-confirm">{ __('Xác Nhận Mật Khẩu') }</label>
                        <div className="password-wrapper">
                            <input
                                type={confirmPasswordVisible ? 'text' : 'password'}
                                className={`form-control ${errors.password_confirmation ? 'is-invalid' : ''}`}
                                id="password-confirm"
                                value={passwordConfirmation}
                                onChange={(e) => setPasswordConfirmation(e.target.value)}
                                required
                                autoComplete="new-password"
                            />
                            <span className="toggle-password" onClick={toggleConfirmPasswordVisibility}>
                                {confirmPasswordVisible ? '🙈' : '👁️'}
                            </span>
                        </div>
                        {errors.password_confirmation && (
                            <span className="invalid-feedback" role="alert">
                                <strong>{errors.password_confirmation}</strong>
                            </span>
                        )}
                    </div>

                    <div className="form-group">
                        <button type="submit" className="btn btn-primary">
                            { __('Đặt Lại Mật Khẩu') }
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}

export default ResetPasswordForm;