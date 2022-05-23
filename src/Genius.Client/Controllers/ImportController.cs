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

    private readonly IStatistics _statistics;

    public ImportController(ILogger<ExportController> logger, IChannel channel, IStatistics statistics)
    {
        _logger = logger;
        _grpcClient = channel.GetGeniusClient<Expert.ExpertClient>();
        _statistics = statistics;
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

        var importedData = TryToParseFile(fileContent);

        if (importedData == null)
            return BadRequest("Serialization failed.");

        if (importedData?.System?.Id < 1)
            return BadRequest("Serialization failed.");

        var mergeStatus = await SystemImporter.MergeSystemsAsync(_grpcClient, iSystemId, importedData);

        if (!mergeStatus)
            return BadRequest("Merging failed.");

        _logger.LogInformation($"Import of the system {importedData.System?.Name} finished.");

        await _statistics.AddAsync(Genius.Protocol.StatisticType.System, $"import.system.{systemId}");

        return Ok("Success");
    }

    [HttpPost]
    [Route("new")]
    public async Task<IActionResult> ImportNewSystem([FromForm] int systemId, [FromForm] IFormFile file,
        [FromForm] string systemName, [FromForm] string systemDescription, [FromForm] string systemQuestion,
        [FromForm] string systemAuthor, [FromForm] string systemSource, [FromForm] string systemType, [FromForm] int systemConfidence)
    {
        if (file == null)
            return BadRequest("File not found.");

        var fileContent = await ReadFileContent(file);

        if (String.IsNullOrEmpty(fileContent))
            return BadRequest("File empty.");

        var importedData = TryToParseFile(fileContent);

        if (importedData == null)
            return BadRequest("Serialization failed.");

        if (String.IsNullOrWhiteSpace(importedData.System?.Name))
            return BadRequest("Serialization failed.");

        if (String.IsNullOrWhiteSpace(importedData.System.Description))
            importedData.System.Description = systemDescription ?? String.Empty;

        if (String.IsNullOrWhiteSpace(importedData.System.Question))
            importedData.System.Question = systemQuestion ?? String.Empty;

        if (String.IsNullOrWhiteSpace(importedData.System.Source))
            importedData.System.Source = systemSource ?? String.Empty;

        if (String.IsNullOrWhiteSpace(importedData.System.Author))
            importedData.System.Author = systemAuthor ?? String.Empty;

        if (importedData.System?.Confidence < 1)
            importedData.System.Confidence = systemConfidence;

        var importResult = await SystemImporter.ImportSystemAsync(_grpcClient, importedData);

        if (importResult < 1)
            return StatusCode(400, 0);

        return Ok(importResult);
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
