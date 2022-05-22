// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;
using System.IO;
using System.Text;
using System.Text.Json;
using System.Threading.Tasks;
using Genius.Client.Export;
using Genius.Client.Import;
using Genius.Client.Interfaces;
using Genius.Protocol;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Logging;

namespace Genius.Client.Controllers;

/// <summary>
/// Provised API for importing the files.
/// </summary>
[ApiController]
[Route("api/import")]
public class ImportController : ControllerBase
{
    private readonly ILogger<ExportController> _logger;

    private readonly Expert.ExpertClient _grpcClient;

    public ImportController(ILogger<ExportController> logger, IChannel channel)
    {
        _logger = logger;
        _grpcClient = channel.GetGeniusClient<Expert.ExpertClient>();
    }

    [HttpPost]
    [Route("")]
    public async Task<IActionResult> ImportSystem([FromForm] string systemId, [FromForm] IFormFile file)
    {
        if (String.IsNullOrEmpty(systemId))
            return BadRequest("System not found.");

        Int32.TryParse(systemId, out int iSystemId);

        if (iSystemId < 1)
            return BadRequest("System not found.");

        if (file == null)
            return BadRequest("File not found.");

        var fileContent = await ReadFileContent(file);

        if (String.IsNullOrEmpty(fileContent))
            return BadRequest("File empty.");

        var exportedData = TryToParseFile(fileContent);

        if (exportedData?.System?.Id < 1)
            return BadRequest("Serialization failed.");

        var mergeStatus = await SystemImporter.MergeSystems(_grpcClient, iSystemId, exportedData);

        if (!mergeStatus)
            return BadRequest("Merging failed.");

        return Ok("Success");
    }

    private async Task<string> ReadFileContent(IFormFile file)
    {
        var result = new StringBuilder();
        using var reader = new StreamReader(file.OpenReadStream());

        while (reader.Peek() >= 0)
            result.AppendLine(await reader.ReadLineAsync());

        reader.Close();

        return result.ToString().Trim();
    }

    private ExportExpertModel TryToParseFile(string rawData)
    {
        try
        {
            var data = JsonSerializer.Deserialize<ExportExpertModel>(rawData);

            return data;
        }
        catch (Exception e)
        {
            return new ExportExpertModel { };
        }
    }
}
