import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import ForgotPasswordForm from './components/ForgotPasswordForm.jsx'; // Corrected filename
import ResetPasswordForm from './components/ResetPasswordForm.jsx';   // Corrected filename

function App() {
    return (
        <Router>
            <Routes>
                <Route path="/forgot-password" element={<ForgotPasswordForm />} />
                <Route path="/reset-password/:token" element={<ResetPasswordForm />} />
                {/* Các route khác của bạn */}
            </Routes>
        </Router>
    );
}

export default App;