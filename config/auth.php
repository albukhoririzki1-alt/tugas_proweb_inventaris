<?php

/**
 * config/auth.php
 * Helper autentikasi & otorisasi berbasis session
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Require user to be logged in.
 * Redirects to login.php if not authenticated.
 */
function requireLogin(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . 'login.php');
        exit;
    }
}

/**
 * Require user to have one of the given roles.
 * @param string|string[] $roles  e.g. 'admin' or ['admin','operator']
 */
function requireRole($roles): void
{
    requireLogin();
    $roles = (array) $roles;
    if (!in_array($_SESSION['peran'] ?? '', $roles, true)) {
        http_response_code(403);
        die('<h2>403 — Akses Ditolak</h2><p>Anda tidak memiliki izin untuk halaman ini.</p>');
    }
}

/**
 * Return current logged-in user data from session, or null.
 */
function getCurrentUser(): ?array
{
    if (empty($_SESSION['user_id'])) return null;
    return [
        'id'       => $_SESSION['user_id'],
        'nama'     => $_SESSION['nama'],
        'username' => $_SESSION['username'],
        'peran'    => $_SESSION['peran'],
    ];
}

/**
 * Check if current user has a given role.
 */
function hasRole(string $role): bool
{
    return ($_SESSION['peran'] ?? '') === $role;
}

/**
 * Check if current user has any of the given roles.
 * @param string[] $roles
 */
function hasAnyRole(array $roles): bool
{
    return in_array($_SESSION['peran'] ?? '', $roles, true);
}
