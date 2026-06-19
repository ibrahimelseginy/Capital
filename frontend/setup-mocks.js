// Setup global mocks for Node environment before running ESM templates
globalThis.window = globalThis;
globalThis.location = { hash: '' };
globalThis.LangManager = {
  get currentLang() { return 'en'; },
  t: function(k) { return k; }
};
