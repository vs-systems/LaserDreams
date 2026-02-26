const fs = require('fs');

try {
    const raw = JSON.parse(fs.readFileSync('raw_products.json', 'utf-8'));

    const categoryMap = {
        1: 'Moving Head',
        2: 'Wash Light',
        3: 'LED Strobe Light',
        4: 'Led Par Light',
        5: 'Laser Light',
        19: 'Studio Series',
        20: 'Effect Series'
    };

    const categories = Object.values(categoryMap).sort();

    const products = raw.map(p => {
        let specs = {};
        // Extracting basic specs from technical_description
        if (p.technical_description) {
            let lines = p.technical_description.split('\\n');
            for (let l of lines) {
                if (l.includes(':')) {
                    let [k, v] = l.split(':');
                    if (k && v && Object.keys(specs).length < 5) {
                        specs[k.trim()] = v.trim();
                    }
                }
            }
        }

        let mainImg = "";
        if (p.images && p.images.length > 0) {
            let img = p.images[0];
            if (!img.startsWith('http')) {
                mainImg = `https://yxldaywdnpzpfeftctpr.supabase.co/storage/v1/object/public/product-images/${img}`;
            } else {
                mainImg = img;
            }
        }

        const name = p.model || "Unknown";
        const categoryName = categoryMap[p.category_id] || `Categoría ${p.category_id}`;

        return {
            id: name.toLowerCase().replace(/[^a-z0-9]+/g, '-'),
            name: name,
            category: categoryName,
            image: mainImg,
            price: "Consultar",
            description: p.description || name,
            specs: specs
        };
    });

    const dataContent = `const catalogData = ${JSON.stringify({ categories, products }, null, 2)};`;
    fs.writeFileSync('js/data.js', dataContent);
    console.log('Successfully generated js/data.js with ' + products.length + ' products.');
} catch (e) {
    console.error("Error generating data:", e);
}
