module.exports = {
  testDir: 'tests',
  use: {
baseURL: process.env.BASE_URL || 'http://localhost:3080',
  headless: true,
  },
  webServer: process.env.CI ? undefined : {
    command: 'node scripts/serve.js',
    url: 'http://localhost:3080',
    reuseExistingServer: true,
  },
};
