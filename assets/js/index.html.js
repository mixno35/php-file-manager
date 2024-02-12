const latest_version = document.getElementById("latest-version");

async function getReleases(owner, repo) {
    const apiUrl = `https://api.github.com/repos/${owner}/${repo}/releases?sort=created&direction=desc&page=1&per_page=1`;

    try {
        const response = await fetch(apiUrl);
        const releases = await response.json();

        releases.forEach(release => console.log("Release", release));

        if (latest_version) {
            latest_version.innerText = `Download latest ${releases[0]["tag_name"]}`;
            latest_version.setAttribute("href", releases[0]["html_url"]);
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

getReleases("mixno35", "file-manager").then();