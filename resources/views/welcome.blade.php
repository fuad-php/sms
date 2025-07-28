<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'School Management System') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 bg-indigo-600 rounded-full flex items-center justify-center">
                    <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m4 0v-5a1 1 0 011-1h4a1 1 0 011 1v5"></path>
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    School Management System
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Sign in to your account
                </p>
            </div>

            <!-- Login Form -->
            <form class="mt-8 space-y-6" id="loginForm">
                <div class="bg-white shadow-lg rounded-lg p-8">
                    <div class="space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                            <input id="email" name="email" type="email" required 
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                                   placeholder="Enter your email">
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input id="password" name="password" type="password" required 
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                                   placeholder="Enter your password">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" id="loginBtn"
                                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            Sign in
                        </button>
                    </div>

                    <!-- Error Message -->
                    <div id="errorMessage" class="mt-4 hidden">
                        <div class="bg-red-50 border border-red-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-800" id="errorText"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <div id="successMessage" class="mt-4 hidden">
                        <div class="bg-green-50 border border-green-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-800" id="successText"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Demo Accounts -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Demo Accounts</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <span class="font-medium text-gray-900">Admin:</span>
                            <span class="text-gray-600">admin@school.com</span>
                        </div>
                        <button onclick="fillCredentials('admin@school.com', 'admin123')" 
                                class="text-indigo-600 hover:text-indigo-800 font-medium">Use</button>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <span class="font-medium text-gray-900">Teacher:</span>
                            <span class="text-gray-600">john.smith@school.com</span>
                        </div>
                        <button onclick="fillCredentials('john.smith@school.com', 'teacher123')" 
                                class="text-indigo-600 hover:text-indigo-800 font-medium">Use</button>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <span class="font-medium text-gray-900">Student:</span>
                            <span class="text-gray-600">alice.wilson@student.school.com</span>
                        </div>
                        <button onclick="fillCredentials('alice.wilson@student.school.com', 'student123')" 
                                class="text-indigo-600 hover:text-indigo-800 font-medium">Use</button>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <span class="font-medium text-gray-900">Parent:</span>
                            <span class="text-gray-600">robert.wilson@parent.school.com</span>
                        </div>
                        <button onclick="fillCredentials('robert.wilson@parent.school.com', 'parent123')" 
                                class="text-indigo-600 hover:text-indigo-800 font-medium">Use</button>
                    </div>
                </div>
            </div>

            <!-- User Info Display -->
            <div id="userInfo" class="bg-white shadow-lg rounded-lg p-6 hidden">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Welcome!</h3>
                <div id="userDetails" class="space-y-2 text-sm"></div>
                <button onclick="logout()" 
                        class="mt-4 w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md">
                    Logout
                </button>
            </div>

            <!-- API Endpoints -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">API Endpoints</h3>
                <div class="text-sm space-y-2">
                    <div><span class="font-medium">Health Check:</span> <code class="text-indigo-600">GET /api/health</code></div>
                    <div><span class="font-medium">Login:</span> <code class="text-indigo-600">POST /api/auth/login</code></div>
                    <div><span class="font-medium">Dashboard:</span> <code class="text-indigo-600">GET /api/dashboard</code></div>
                    <div><span class="font-medium">Students:</span> <code class="text-indigo-600">GET /api/students</code></div>
                    <div><span class="font-medium">Attendance:</span> <code class="text-indigo-600">GET /api/attendance</code></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set up axios defaults
        axios.defaults.baseURL = window.location.origin + '/api';
        axios.defaults.headers.common['Content-Type'] = 'application/json';
        axios.defaults.headers.common['Accept'] = 'application/json';

        // Add token to requests if available
        const token = localStorage.getItem('token');
        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            checkAuthStatus();
        }

        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }

        function showError(message) {
            document.getElementById('errorText').textContent = message;
            document.getElementById('errorMessage').classList.remove('hidden');
            document.getElementById('successMessage').classList.add('hidden');
        }

        function showSuccess(message) {
            document.getElementById('successText').textContent = message;
            document.getElementById('successMessage').classList.remove('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        function hideMessages() {
            document.getElementById('errorMessage').classList.add('hidden');
            document.getElementById('successMessage').classList.add('hidden');
        }

        function displayUserInfo(user) {
            const userDetails = document.getElementById('userDetails');
            userDetails.innerHTML = `
                <div><span class="font-medium">Name:</span> ${user.name}</div>
                <div><span class="font-medium">Email:</span> ${user.email}</div>
                <div><span class="font-medium">Role:</span> <span class="capitalize">${user.role}</span></div>
                ${user.phone ? `<div><span class="font-medium">Phone:</span> ${user.phone}</div>` : ''}
            `;
            
            document.getElementById('loginForm').classList.add('hidden');
            document.getElementById('userInfo').classList.remove('hidden');
        }

        function hideUserInfo() {
            document.getElementById('loginForm').classList.remove('hidden');
            document.getElementById('userInfo').classList.add('hidden');
        }

        async function checkAuthStatus() {
            try {
                const response = await axios.get('/auth/me');
                if (response.data.success) {
                    displayUserInfo(response.data.user);
                    showSuccess('You are logged in!');
                }
            } catch (error) {
                localStorage.removeItem('token');
                delete axios.defaults.headers.common['Authorization'];
            }
        }

        function logout() {
            localStorage.removeItem('token');
            delete axios.defaults.headers.common['Authorization'];
            hideUserInfo();
            hideMessages();
        }

        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const loginBtn = document.getElementById('loginBtn');
            
            loginBtn.disabled = true;
            loginBtn.textContent = 'Signing in...';
            hideMessages();
            
            try {
                const response = await axios.post('/auth/login', {
                    email: email,
                    password: password
                });
                
                if (response.data.success) {
                    const token = response.data.access_token;
                    localStorage.setItem('token', token);
                    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                    
                    displayUserInfo(response.data.user);
                    showSuccess(`Welcome back, ${response.data.user.name}!`);
                } else {
                    showError('Login failed. Please try again.');
                }
            } catch (error) {
                if (error.response && error.response.data) {
                    showError(error.response.data.message || 'Login failed');
                } else {
                    showError('Network error. Please check your connection.');
                }
            } finally {
                loginBtn.disabled = false;
                loginBtn.textContent = 'Sign in';
            }
        });

        // Test API health on page load
        async function checkApiHealth() {
            try {
                const response = await axios.get('/health');
                console.log('API Health:', response.data);
            } catch (error) {
                console.error('API Health Check Failed:', error);
            }
        }

        checkApiHealth();
    </script>
</body>
</html>
