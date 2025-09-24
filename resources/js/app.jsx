import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, Link, useNavigate, Navigate } from 'react-router-dom';
import axios from 'axios';

// --- Axios Setup ---
// Configure axios to send credentials (like cookies) with every request
axios.defaults.withCredentials = true;
// Set the base URL for all API requests to your Laravel backend
axios.defaults.baseURL = "http://127.0.0.1:8000"; 

// --- Main App Component ---
function App() {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);

    // Effect to check for an authenticated user on initial load
    useEffect(() => {
        axios.get('/api/user')
            .then(response => {
                setUser(response.data);
            })
            .catch(() => {
                setUser(null);
            })
            .finally(() => {
                setLoading(false);
            });
    }, []);

    // Function to handle user logout
    const handleLogout = () => {
        axios.post('/api/logout').then(() => {
            setUser(null);
        });
    };

    // Show a loading indicator while checking auth status
    if (loading) {
        return <div className="flex items-center justify-center h-screen"><div className="text-xl font-semibold">Loading...</div></div>;
    }

    return (
        <Router>
            <Routes>
                {/* Public Routes */}
                <Route path="/" element={user ? <Navigate to="/dashboard" /> : <LandingPage />} />
                <Route path="/login" element={user ? <Navigate to="/dashboard" /> : <LoginPage setUser={setUser} />} />
                <Route path="/register" element={user ? <Navigate to="/dashboard" /> : <RegisterPage />} />

                {/* Protected Route */}
                <Route 
                    path="/dashboard/*" 
                    element={
                        user 
                        ? <DashboardLayout user={user} handleLogout={handleLogout} /> 
                        : <Navigate to="/login" />
                    } 
                />
            </Routes>
        </Router>
    );
}

// --- Page & Layout Components ---

const LandingPage = () => (
    <div className="min-h-screen bg-white">
        <header className="p-4 flex justify-between items-center max-w-7xl mx-auto">
             <h1 className="text-2xl font-bold">DataMate</h1>
        </header>
        <main className="flex flex-col items-center justify-center text-center py-20">
            <h2 className="text-5xl font-extrabold mb-4">Connect Data Analysts with Clients</h2>
            <p className="text-gray-600 mb-12 max-w-2xl">
                Bridge the gap between skilled data analysts and businesses seeking insights. Join our platform to find the perfect match for your data needs.
            </p>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl">
                <div className="border rounded-lg p-8 flex flex-col items-center">
                    <div className="bg-gray-100 rounded-full p-3 mb-4">
                       {/* User Plus Icon */}
                        <svg className="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                    </div>
                    <h3 className="text-xl font-semibold mb-2">Create Account</h3>
                    <p className="text-gray-500 mb-6">New to DataMate? Sign up as a client or an analyst.</p>
                    <ul className="text-left text-gray-600 space-y-2 mb-8">
                        <li>• Choose your role (Client or Analyst)</li>
                        <li>• Set up your profile</li>
                        <li>• Start connecting immediately</li>
                    </ul>
                    <Link to="/register" className="w-full bg-gray-900 text-white font-semibold py-3 rounded-lg hover:bg-gray-800 transition">
                        Sign Up
                    </Link>
                </div>
                <div className="border rounded-lg p-8 flex flex-col items-center bg-white">
                     <div className="bg-gray-100 rounded-full p-3 mb-4">
                        {/* Login Icon */}
                        <svg className="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                    </div>
                    <h3 className="text-xl font-semibold mb-2">Sign In</h3>
                    <p className="text-gray-500 mb-6">Already have an account? Welcome back!</p>
                     <ul className="text-left text-gray-600 space-y-2 mb-8">
                        <li>• Access your dashboard</li>
                        <li>• Manage your profile</li>
                        <li>• Continue your connections</li>
                    </ul>
                    <Link to="/login" className="w-full bg-white text-gray-900 border border-gray-300 font-semibold py-3 rounded-lg hover:bg-gray-100 transition">
                        Sign In
                    </Link>
                </div>
            </div>
        </main>
    </div>
);

const LoginPage = ({ setUser }) => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const navigate = useNavigate();

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        try {
            await axios.get('/sanctum/csrf-cookie');
            const response = await axios.post('/api/login', { email, password });
            setUser(response.data.user);
            navigate('/dashboard');
        } catch (err) {
            setError('Invalid credentials. Please try again.');
            console.error(err);
        }
    };

    return (
         <div className="min-h-screen bg-gray-50 flex flex-col justify-center items-center">
            <div className="w-full max-w-md">
                <div className="text-center mb-8">
                    <Link to="/" className="text-sm text-gray-600 hover:text-gray-900">← Back to Home</Link>
                </div>
                <div className="bg-white p-8 border border-gray-200 rounded-lg shadow-sm">
                     <div className="flex flex-col items-center text-center mb-6">
                        <div className="bg-gray-100 rounded-full p-3 mb-4 border">
                            <svg className="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        </div>
                        <h2 className="text-2xl font-bold">Welcome Back</h2>
                        <p className="text-gray-500">Sign in to your DataMate account</p>
                    </div>
                    
                    <form onSubmit={handleSubmit} className="space-y-6">
                         <div>
                            <label className="text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" value={email} onChange={e => setEmail(e.target.value)} required className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm" />
                        </div>
                        <div>
                            <label className="text-sm font-medium text-gray-700">Password</label>
                            <input type="password" value={password} onChange={e => setPassword(e.target.value)} required className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm" />
                        </div>
                        {error && <p className="text-red-500 text-sm text-center">{error}</p>}
                        <button type="submit" className="w-full bg-gray-900 text-white font-semibold py-3 rounded-lg hover:bg-gray-800 transition">Sign In</button>
                    </form>
                     <div className="text-center mt-6">
                        <p className="text-sm text-gray-600">
                            Don't have an account? <Link to="/register" className="font-semibold text-gray-900 hover:underline">Sign up here</Link>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
};

const RegisterPage = () => {
    const [formData, setFormData] = useState({
        username: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'CLIENT'
    });
    const [error, setError] = useState('');
    const navigate = useNavigate();

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        if (formData.password !== formData.password_confirmation) {
            setError("Passwords do not match.");
            return;
        }
        try {
            await axios.post('/api/register', formData);
            navigate('/login');
        } catch (err) {
            setError('Failed to register. Please check your input.');
            console.error(err);
        }
    };

    return (
         <div className="min-h-screen bg-gray-50 flex flex-col justify-center items-center">
            <div className="w-full max-w-md">
                 <div className="text-center mb-8">
                    <Link to="/" className="text-sm text-gray-600 hover:text-gray-900">← Back to Home</Link>
                </div>
                <div className="bg-white p-8 border border-gray-200 rounded-lg shadow-sm">
                     <div className="flex flex-col items-center text-center mb-6">
                         <div className="bg-gray-100 rounded-full p-3 mb-4 border">
                            <svg className="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                        </div>
                        <h2 className="text-2xl font-bold">Create Your Account</h2>
                        <p className="text-gray-500">Join DataMate to start connecting with data professionals</p>
                    </div>
                    
                    <form onSubmit={handleSubmit} className="space-y-4">
                        <InputField label="Full Name" name="username" value={formData.username} onChange={handleChange} />
                        <InputField label="Email Address" name="email" type="email" value={formData.email} onChange={handleChange} />
                        <InputField label="Password" name="password" type="password" value={formData.password} onChange={handleChange} placeholder="At least 6 characters" />
                        <InputField label="Confirm Password" name="password_confirmation" type="password" value={formData.password_confirmation} onChange={handleChange} placeholder="Repeat your password"/>
                        <div>
                             <label className="text-sm font-medium text-gray-700">I am a...</label>
                             <select name="role" value={formData.role} onChange={handleChange} className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm">
                                 <option value="CLIENT">Client</option>
                                 <option value="ANALYST">Analyst</option>
                             </select>
                        </div>

                        {error && <p className="text-red-500 text-sm text-center">{error}</p>}
                        <button type="submit" className="w-full bg-gray-900 text-white font-semibold py-3 rounded-lg hover:bg-gray-800 transition">Create Account</button>
                    </form>
                     <div className="text-center mt-6">
                        <p className="text-sm text-gray-600">
                           Already have an account? <Link to="/login" className="font-semibold text-gray-900 hover:underline">Sign in instead</Link>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
};

const InputField = ({ label, ...props }) => (
    <div>
        <label className="text-sm font-medium text-gray-700">{label}</label>
        <input {...props} required className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm" />
    </div>
);


const DashboardLayout = ({ user, handleLogout }) => (
    <div className="min-h-screen bg-gray-100">
        <header className="bg-white shadow-sm">
            <div className="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 className="text-2xl font-bold text-gray-900">DataMate</h1>
                <div>
                    <span className="mr-4">Welcome, {user.username}!</span>
                    <button onClick={handleLogout} className="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700">Logout</button>
                </div>
            </div>
        </header>
        <main className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
           {user.role === 'CLIENT' ? <ClientDashboard /> : <AnalystDashboard />}
        </main>
    </div>
);


// --- Role-Specific Dashboards ---

const ClientDashboard = () => {
    const [analysts, setAnalysts] = useState([]);

    useEffect(() => {
        axios.get('/api/analysts')
            .then(response => setAnalysts(response.data))
            .catch(error => console.error("Could not fetch analysts:", error));
    }, []);

    return (
        <div>
            <div className="flex justify-between items-center mb-6">
                 <h2 className="text-3xl font-bold">Find Data Analysts</h2>
                 {/* Search and Sort can be added here */}
            </div>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {analysts.map(analyst => <AnalystCard key={analyst.user_id} analyst={analyst} />)}
            </div>
        </div>
    );
};

const AnalystCard = ({ analyst }) => (
    <div className="bg-white border rounded-lg shadow-sm p-6 flex flex-col">
        <div className="flex justify-between items-start mb-2">
            <div>
                 <h3 className="text-xl font-bold">{analyst.username}</h3>
                 <p className="text-gray-600">{analyst.analyst_profile?.professional_title || 'Data Analyst'}</p>
            </div>
             <span className={`px-3 py-1 text-sm rounded-full ${analyst.analyst_profile?.status === 'AVAILABLE' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}`}>
                {analyst.analyst_profile?.status || 'Available'}
             </span>
        </div>
        <p className="text-gray-500 text-sm mb-4">
           {analyst.analyst_profile?.location || 'Remote'} • {analyst.analyst_profile?.years_of_experience || '2+'} years
        </p>
        <p className="text-gray-700 mb-4 flex-grow">
            {analyst.analyst_profile?.professional_description || 'Experienced data professional ready to help.'}
        </p>
        <div className="flex flex-wrap gap-2 mb-4">
           {analyst.analyst_profile?.skills?.split(',').map(skill => 
              <span key={skill} className="bg-gray-200 text-gray-800 px-2 py-1 rounded-md text-xs font-medium">{skill.trim()}</span>
           )}
        </div>
        <div className="flex justify-between items-center border-t pt-4">
             <div className="text-lg font-semibold">
                ${analyst.analyst_profile?.hourly_rate || 'N/A'}/hr
            </div>
            <button className="bg-gray-900 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-800 transition">
                Contact Analyst
            </button>
        </div>
    </div>
);


const AnalystDashboard = () => {
    // This would be the view where an analyst can see their profile, edit it, and view incoming orders.
    // For now, it's a placeholder.
    return (
        <div className="bg-white p-8 rounded-lg shadow-sm">
            <h2 className="text-3xl font-bold mb-4">Analyst Profile</h2>
             <p>This is where you will edit your professional profile, manage your services, and view incoming client orders.</p>
            {/* The Analyst Profile Form would go here */}
        </div>
    );
};


export default App;

