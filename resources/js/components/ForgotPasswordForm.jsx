import React, { useState } from 'react';

function ForgotPasswordForm() {
    const [email, setEmail] = useState('');
    const [status, setStatus] = useState(null);
    const [errors, setErrors] = useState({});

    const handleSubmit = (event) => {
        event.preventDefault();
        const formData = { email };

        fetch('/forgot-password', {
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
        })
        .catch(error => {
            console.error('Lỗi:', error);
            setErrors({ general: 'Đã có lỗi xảy ra. Vui lòng thử lại.' });
            setStatus(null);
        });
    };

    return (
        <div className="reset-container">
            <div className="card-header">{__('Quên Mật Khẩu')}</div>
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
                            autoFocus
                        />
                        {errors.email && (
                            <span className="invalid-feedback" role="alert">
                                <strong>{errors.email}</strong>
                            </span>
                        )}
                    </div>

                    <div className="form-group">
                        <button type="submit" className="btn btn-primary">
                            { __('Gửi liên kết đặt lại mật khẩu') }
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}

export default ForgotPasswordForm;