const fs = require('fs');

async function scrape() {
    try {
        const resp = await fetch('https://sanyilights.com.ar/assets/index-B5XA1EGh.js');
        const text = await resp.text();
        const keyMatch = text.match(/eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9\.[a-zA-Z0-9_\-]+\.[a-zA-Z0-9_\-]+/);

        if (!keyMatch) {
            console.log("No key found.");
            return;
        }
        const anonKey = keyMatch[0];
        const supabaseUrl = 'https://yxldaywdnpzpfeftctpr.supabase.co';

        const productsRes = await fetch(`${supabaseUrl}/rest/v1/products?select=*`, {
            headers: { 'apikey': anonKey, 'Authorization': `Bearer ${anonKey}` }
        });

        const products = await productsRes.json();

        fs.writeFileSync('raw_products.json', JSON.stringify(products, null, 2));
        console.log(`Saved ${products.length} products to raw_products.json`);
    } catch (err) {
        console.error(err);
    }
}
scrape();
