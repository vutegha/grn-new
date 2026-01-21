<?php
// Générer une image placeholder SVG pour les services
header('Content-Type: image/svg+xml');
echo '<?xml version="1.0" encoding="UTF-8"?>
<svg width="400" height="300" viewBox="0 0 400 300" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#3B82F6;stop-opacity:0.1" />
      <stop offset="100%" style="stop-color:#1E40AF;stop-opacity:0.2" />
    </linearGradient>
  </defs>
  <rect width="400" height="300" fill="url(#grad1)"/>
  <g transform="translate(200,120)">
    <circle cx="0" cy="0" r="40" fill="#9CA3AF" opacity="0.6"/>
    <path d="M -20,-10 L -20,10 L 0,0 L 20,10 L 20,-10 Z" fill="#6B7280"/>
    <rect x="-25" y="20" width="50" height="8" rx="4" fill="#6B7280" opacity="0.8"/>
    <rect x="-15" y="35" width="30" height="6" rx="3" fill="#9CA3AF" opacity="0.6"/>
  </g>
  <text x="200" y="220" text-anchor="middle" font-family="Arial, sans-serif" font-size="14" fill="#6B7280">Image de service</text>
</svg>';
?>
