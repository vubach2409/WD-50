import React from 'react';
import ReactDOM from 'react-dom/client';
import ResetPasswordForm from './components/ResetPasswordForm.jsx';
import './bootstrap';
import { BrowserRouter, Routes, Route, useParams, useSearchParams } from 'react-router-dom';

const ResetPasswordWrapper = () => {
    const params = useParams();
    const searchParams = useSearchParams();

    return <ResetPasswordForm token={params.token} email={searchParams.get('email')} />;
};

const root = ReactDOM.createRoot(document.getElementById('react-reset-password-form'));
root.render(
    <BrowserRouter>
        <Routes>
            <Route path="/reset-password/:token" element={<ResetPasswordWrapper />} />
        </Routes>
    </BrowserRouter>
);